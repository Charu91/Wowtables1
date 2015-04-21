<?php namespace WowTables\Http\Controllers;

use Illuminate\Http\Request;
use WowTables\Http\Models\Eloquent\EmailFooterPromotion;
use WowTables\Http\Requests\CreateEmailFooterPromotionsRequest;
use WowTables\Http\Requests\EditEmailFooterPromotionsRequest;

class AdminEmailFooterPromotionsController extends Controller {


    function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $email_footer_promotions = EmailFooterPromotion::all();

        return view('admin.promotions.email_footer_promotions.index',['email_footer_promotions'=>$email_footer_promotions]);
    }

    public function create()
    {
        return view('admin.promotions.email_footer_promotions.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserAttributeRequest $request
     * @return Response
     */
    public function store(CreateEmailFooterPromotionsRequest $request)
    {
        $email_footer_promotions = new EmailFooterPromotion();

        $email_footer_promotions->link = $this->request->get('link');
        $email_footer_promotions->media_id = $this->request->get('media_id');
        $email_footer_promotions->city_id = $this->request->get('location_id');
        $email_footer_promotions->show_in_experience = ($this->request->get('show_in_experience') ? $this->request->get('show_in_experience') : 0);
        $email_footer_promotions->show_in_alacarte = ($this->request->get('show_in_alacarte') ? $this->request->get('show_in_alacarte') : 0);

        $email_footer_promotions->save();

        flash()->success('The Footer Promotion has been created successfully');

        return redirect('admin/promotions/email_footer_promotions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $email_footer_promotions = EmailFooterPromotion::find($id);

        return view('admin.promotions.email_footer_promotions.edit',['email_footer_promotions'=>$email_footer_promotions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param EditUserAttributeRequest $request
     * @return Response
     */
    public function update($id,EditEmailFooterPromotionsRequest $request)
    {
        $email_footer_promotions = EmailFooterPromotion::find($id);

        $email_footer_promotions->link = $this->request->get('link');
        $email_footer_promotions->media_id = $this->request->get('media_id');
        $email_footer_promotions->city_id = $this->request->get('location_id');
        $email_footer_promotions->show_in_experience = ($this->request->get('show_in_experience') ? $this->request->get('show_in_experience') : 0);
        $email_footer_promotions->show_in_alacarte = ($this->request->get('show_in_alacarte') ? $this->request->get('show_in_alacarte') : 0);

        $email_footer_promotions->save();

        flash()->success('The Footer Promotion has been edited successfully');

        return redirect('admin/promotions/email_footer_promotions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        EmailFooterPromotion::destroy($id);

        flash()->success('The Footer Promotion has been deleted successfully');
    }

}
