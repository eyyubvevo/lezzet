<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Footer extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
//        $menus = [
//            ['name' => 'Ana sehife','url' => route('home')],
//            ['name' => 'Haqqımızda','url' => route('about')],
//            ['name' => 'Partnyorlar' ,'url' => route('partners')],
//            ['name' => 'Tez-tez verilən suallar' ,'url' => route('general_questions')],
//            ['name' => 'Blog' ,'url' => route('news')],
//            ['name' => 'Əlaqə' ,'url' => route('contact')],
//        ];
        return view('components.footer');
    }
}
