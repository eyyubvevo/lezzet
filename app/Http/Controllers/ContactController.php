<?php

namespace App\Http\Controllers;

use App\Repositories\EloquentContactRepository;
use App\Repositories\EloquentPageMetaTagRepository;
use Butschster\Head\Facades\Meta;
use Butschster\Head\MetaTags\Entities\Webmaster;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    protected EloquentContactRepository $contactRepository;
    protected EloquentPageMetaTagRepository $pageMetaTagRepository;
    public function __construct(
        EloquentContactRepository $contactRepository,
        EloquentPageMetaTagRepository     $pageMetaTagRepository

    )
    {
        $this->contactRepository = $contactRepository;
        $this->pageMetaTagRepository = $pageMetaTagRepository;
    }

    public function index()
    {
        $filters = [
            'page_name' => ['operator' => '=', 'value' => 'Əlaqə'],
        ];
        $columns = ['id', 'title', 'description', 'keywords'];
        $page_meta_tag = $this->pageMetaTagRepository->first(
            filters: $filters,
            columns: $columns,
        );
        if ($page_meta_tag) {
            Meta::setTitleSeparator('->')
                ->prependTitle(setting('title'))
                ->setTitle($page_meta_tag->getTranslation('title', app()->getLocale()))
                ->setDescription($page_meta_tag->getTranslation('description', app()->getLocale()))
                ->setKeywords($page_meta_tag->getTranslation('keywords', app()->getLocale()))
                ->setRobots('Vaqif,Shamil')
                ->setContentType('text/html')
                ->setViewport('width=device-width, initial-scale=1')
                ->addWebmaster(Webmaster::GOOGLE, 'f+e-Ww4=[Pp4wyEPLdVx4LxTsQ')
                ->setCanonical(config('app.url'));
        }
        return view('front.contact');
    }

    public function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ];
        try {
            $relatedData = [];
            $contact = $this->contactRepository->create(data: $data, relatedData: $relatedData);
            if ($contact) {
                flash()->addFlash('success', 'Təşəkkürlər', 'Müraciətiniz uğurla göndərildi', ['timeout' => 3000, 'position' => 'top-center']);
                return redirect()->back();
            } else {
                flash()->addFlash('error', 'ERROR', 'Xəta baş verdi', ['timeout' => 3000, 'position' => 'top-center']);
            }
        } catch (\Exception $e) {
            flash()->addFlash('warning', 'Xəbərdarlıq', $e->getMessage(), ['timeout' => 3000, 'position' => 'top-center']);
        }
    }
}
