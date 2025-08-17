<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('status', '로그아웃되었습니다.');
    }

    public function Login(Request $request)
    {
        // 1. 입력값 검증
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
        }catch(ValidationException $e){
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', '입력값 검증 실패');
        }
        // 2. 인증 시도
        if (Auth::attempt($credentials, false)) {
            $request->session()->regenerate();
            // 로그인 성공시 메인 페이지 또는 원하는 경로로 이동
            return redirect()
                ->intended('/')
                ->with('success', '로그인에 성공하였습니다.');
        }
        // 3. 인증 실패 시
        return redirect()
            ->back()
            ->withInput()
            ->with('error', '입력한 자격 증명이 올바르지 않습니다.');
    }
}
