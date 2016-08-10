<?php

namespace Allison\Http\Controllers\AdManager;

use Illuminate\Http\Request;

use Allison\Http\Requests;
use Allison\Http\Controllers\Controller;
use Allison\Repositories\Contracts\IfFbAdCreativeRepository;
use Allison\Repositories\Contracts\IfFbAdMediaRepository;
use Allison\AllisonFbApiHelpers\helpers\Fb_AdCreativeHelper;
use Allison\AllisonFbApiHelpers\helpers\Fb_AdUtilities;
use Allison\Http\Requests\FbAdCreativeCallToActionRequest;
use Session;
use Illuminate\Support\Facades\Input;


class AdCreativeVideoPageController extends Controller
{
    
    public function __construct(IfFbAdCreativeRepository $fb_adcreative, IfFbAdMediaRepository $fb_admedia)
    {
        $this->fb_adcreative = $fb_adcreative;
        $this->fb_admedia = $fb_admedia;
           
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admanager.adcreative-video-page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FbAdCreativeCallToActionRequest $request, Fb_AdCreativeHelper $ad_adcreative_helper)
    {
        $ad_creative = $this->fb_adcreative->createVideoPageLikeAd($request, $ad_adcreative_helper);
        
        if(!is_object($ad_creative)){
            
           Session::flash('alert-success',Fb_AdUtilities::dumpCreateSuccessMessage());
        }else{
            
            Session::flash('alert-danger',$ad_creative->getExceptionMessage());
            return redirect('/Exception/'.$ad_creative->getExceptionCode());
        }
        
        return redirect('ad/ad-creative');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $adcreative = $this->fb_adcreative->getAdCreative($id);
        
        return view('admanager.adcreative-video-page.update', compact('adcreative'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FbAdCreativeCallToActionRequest $request, Fb_AdCreativeHelper $ad_adcreative_helper, $id)
    {
        $ad_creative = $this->fb_adcreative->updateVideoPageLikeAd($request, $ad_adcreative_helper, $id);
        
         if(!is_object($ad_creative)){
           Session::flash('alert-success',Fb_AdUtilities::dumpCreateSuccessMessage());
        }else{
            Session::flash('alert-danger',$ad_creative->getExceptionMessage());
            return redirect('/Exception/'.$ad_creative->getExceptionCode());
        }
        
        return redirect('ad/ad-creative');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
    
    public function fetchThumbImageUrl(Fb_AdCreativeHelper $ad_adcreative_helper, Request $request){
        
        $video_id = Input::get('video_id');
        
        
        $results = $ad_adcreative_helper->getVideoThumbImageUrl($video_id);

        if($request->wantsJson()){
           return json_encode($results);
        }

        return view('admanager.adcreative-video-page._thumbs', compact('results'));
        //dd($result);
    }
}
