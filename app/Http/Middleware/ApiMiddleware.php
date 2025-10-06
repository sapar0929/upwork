<?php

namespace App\Http\Middleware;

use App\Models\IpAddress;
use App\Models\UserAgent;
use App\Models\Visitor;
use Closure;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // IP ADDRESS
        $reader = new Reader(storage_path('app/GeoLite2-City.mmdb'));
        $ip = $request->ip();
        try {
            $record = $reader->city($ip);
            $ip_address = IpAddress::firstOrCreate([
                'ip_address' => $ip,
                'country_code' => isset($record->country->isoCode) ? $record->country->isoCode : null,
                'country_name' => isset($record->country->name) ? $record->country->name : null,
                'city_name' => isset($record->city->name) ? $record->city->name : null,
            ]);
        } catch (AddressNotFoundException $e) {
            $ip_address = IpAddress::firstOrCreate([
                'ip_address' => $ip,
            ]);
        }
        if ($ip_address->disabled) {
            return response()->json([
                'status' => 0,
            ], Response::HTTP_NOT_FOUND);
        }

        // USER AGENT
        $ua = $request->userAgent();
        $agent = new Agent();
        $agent->setUserAgent($ua);
        $user_agent = UserAgent::firstOrCreate([
            'user_agent' => str($ua)->substr(0, 255),
            'device' => $agent->device() ? str($agent->device())->substr(0, 255) : null,
            'platform' => $agent->platform() ? str($agent->platform())->substr(0, 255) : null,
            'browser' => $agent->browser() ? str($agent->browser())->substr(0, 255) : null,
            'robot' => $agent->robot() ? str($agent->robot())->substr(0, 255) : null,
        ]);
        if ($user_agent->disabled) {
            return response()->json([
                'status' => 0,
            ], Response::HTTP_NOT_FOUND);
        }
        $robot = $agent->isRobot();
        if ($robot) {
            return response()->json([
                'status' => 0,
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $v = Visitor::where('ip_address_id', $ip_address->id)
                ->where('user_agent_id', $user_agent->id)
                ->where('robot', $robot)
                ->where('api', 1)
                ->where('updated_at', '>=', today())
                ->firstOrFail();
            if ($v->disabled) {
                return response()->json([
                    'status' => 0,
                ], Response::HTTP_NOT_FOUND);
            }
            if ($v->suspect_hits > 100) {
                $v->disabled = 1;
                $v->update();
                return response()->json([
                    'status' => 0,
                ], Response::HTTP_NOT_FOUND);
            }
            if ($v->updated_at == now()->toDateTimeString()) {
                $v->increment('suspect_hits');
            }
            $v->increment('hits');
        } catch (ModelNotFoundException $e) {
            $obj = new Visitor();
            $obj->ip_address_id = $ip_address->id;
            $obj->user_agent_id = $user_agent->id;
            $obj->robot = $robot;
            $obj->api = 1;
            $obj->save();
        }

        return $next($request);
    }
}
