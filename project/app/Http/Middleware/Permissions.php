<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class Permissions
{

    public function handle($request, Closure $next,$data)
    {
        if (Auth::guard('admin')->check()) {
            if(Auth::guard('admin')->user()->id == 1){
                return $next($request);
            }
            if(Auth::guard('admin')->user()->role_id == 0){
                return redirect()->route('admin.dashboard')->with('unsuccess',"You don't have access to that section"); 
            }
            
            // Support multiple permissions with OR logic (separator: |)
            $permissions = explode('|', $data);
            foreach($permissions as $permission) {
                if (Auth::guard('admin')->user()->sectionCheck(trim($permission))){
                    return $next($request);
                }
            }
        }
        return redirect()->route('admin.dashboard')->with('unsuccess',"You don't have access to that section"); 
    }
}
