<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Store;
use App\Models\Domain;
use App\Models\Package;
use App\Models\Download;
use App\Models\Timeline;
use App\Models\DomainQuota;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Models\Documentation;
use Illuminate\Support\Facades\Auth;

class JustOrangeController extends Controller
{
    public function index(): \Inertia\Response
    {
        $ads = [];
        $img = glob(public_path('img/*'));
        foreach ($img as $i) {
            $ads[] = url('img/' . basename($i));
        }


        $props['myAdsense'] = $ads;
        $props['packages'] = Package::where('active', true)->orderBy('price', 'asc')->get();
        $props['testimonials'] = Testimonial::where('active', true)->get();
        $data['props'] = $props;
        return Inertia::render('justorange-default', $data);
    }
    public function plan(Request $request)
    {
        if (auth()->check()) {
            $user = User::find(auth()->user()->id);
            $customer = [
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'auth' => true
            ];
        } else {
            $customer = [
                'name' => '',
                'phone' => '',
                'email' => '',
                'auth' => false
            ];
        };
        $plan_name = $request->plan_name;
        $package = Package::where('name', 'LIKE', '%' . $plan_name . '%')->first();
        $props['packageActive'] = $package;
        $props['packages'] = Package::where('active', true)->get();
        $props['customerDetails'] = $customer;
        $data['props'] = $props;
        return Inertia::render('plan', $data);
    }

    public function releases(Request $request)
    {
        $sign = $request->signature;;
        $domain = Domain::where('signature', $sign)->first();
        if ($domain) {
            $download = Download::orderBy('id', 'desc')->first();
            $data['name'] = $download->name;
            $data['version'] = $download->version;
            $data['download_url'] = $download->file_url;
            $data['changelog'] = $download->changelog;
            $data['author'] = '@javaradigital';
            return response()->json($data, 200, [], JSON_PRETTY_PRINT);
        } else {
            return response()->json(['error' => 'Not valid signature'], 201, [], JSON_PRETTY_PRINT);
        }
    }

    public function docUrls()
    {
        // $docs = Documentation::select('id','title','slug','order')->where('is_published', true)->orderBy('order','asc')->get();
        $docs = Documentation::select('id', 'title', 'slug', 'order')
            ->where('is_published', true)
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($doc) {
                $doc->url = url('/docs/' . $doc->slug);
                return $doc;
            });


        return response()->json(['success' => true, 'data' => $docs], 200, [], JSON_PRETTY_PRINT);
    }
    public function invoice(Request $request)
    {
        //  dd($request->invoice);
        $domain = DomainQuota::where('invoice', $request->invoice)->firstOrFail();
        $props['domain'] = $domain;
        $props['user'] = User::find($domain->user_id);
        $data['props'] = $props;
        return Inertia::render('Invoice', $data);
    }
    public function changelog(Request $request)
    {
        if ($request->type == 'all') {

            $download = Download::select('version', 'tag_name', 'changelog', 'id')->orderBy('version', 'desc')->get();
            return response()->json(['success' => true, 'data' => ['release' => $download]], 200, [], JSON_PRETTY_PRINT);
        } elseif ($request->type == 'latest') {

            $download = Download::select('version', 'tag_name', 'changelog', 'id')->orderBy('version', 'desc')->first();
            return response()->json(['success' => true, 'data' => ['release' => $download]], 200, [], JSON_PRETTY_PRINT);
        }
    }
    public function adiyh_store(Request $request)
    {

        // check user-agent is Adiyh-Plugin
        // $userAgent = $request->header('User-Agent');
        // if (strpos($userAgent, 'Adiyh-Plugin') === false) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }
        $products = Store::orderBy('id', 'desc')->get()->map(function ($product) {
            // Check if image already starts with http:// or https://
            $imageUrl = $product->image;

            if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                // If it's already a full URL, return as is
                $productArray = $product->toArray();
                $productArray['image_url'] = $imageUrl;
                return $productArray;
            }

            // If not a full URL, generate asset URL
            $productArray = $product->toArray();
            $productArray['image_url'] = $product->image
                ? asset('storage/' . $product->image)
                : null;

            return $productArray;
        })->toArray();

        // Rest of the existing code remains the same
        $search = $request->query('search', '');
        $page = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 9);

        if (!empty($search)) {
            $products = array_filter($products, function ($product) use ($search) {
                return stripos($product['name'], $search) !== false;
            });
        }

        $total = count($products);
        $offset = ($page - 1) * $perPage;
        $pagedProducts = array_slice($products, $offset, $perPage);

        return response()->json([
            'success' => true,
            'data' => array_values($pagedProducts),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage
        ], 200, [], JSON_PRETTY_PRINT);
    }


    public function planInvoice(Request $request)
    {

        $props['invoiceId'] = $request->invoice;
        $data['props'] = $props;
        return Inertia::render('plan-invoice', $data);
    }


    public function dlFlow(Request $request)
    {
        $uniqid = $request->uniqid;
        $flow = \App\Models\Flow::where('uniqid', $uniqid)->first();
        // get flow storage/app/stubs/flow.php
        $flowPath = storage_path('app/stubs/flows.php');
        $flowContent = file_get_contents($flowPath);

        $flowContent = str_replace('{uniqid}', $uniqid, $flowContent);
        $flowContent = str_replace('{flow_name}', $flow->name, $flowContent);
        $flowContent = str_replace('{render_white_page}', $flow->render_white_page, $flowContent);
        $flowContent = str_replace('{white_page_url}', $flow->white_page_url, $flowContent);
        $flowContent = str_replace('{render_bot_page}', $flow->render_bot_page, $flowContent);
        $flowContent = str_replace('{bot_page_url}', $flow->bot_page_url, $flowContent);
        $flowContent = str_replace('{render_offer_page}', $flow->render_offer_page, $flowContent);
        $flowContent = str_replace('{offer_page_url}', $flow->offer_page_url, $flowContent);
        $flowContent = str_replace('{allowed_countries}', strtoupper(implode(',', $flow->allowed_countries)), $flowContent);
        $flowContent = str_replace('{block_vpn}', $flow->block_vpn, $flowContent);
        $flowContent = str_replace('{block_no_referer}', $flow->block_no_referer, $flowContent);
        $flowContent = str_replace('{allowed_params}', $flow->allowed_params, $flowContent);
        $flowContent = str_replace('{acs}', $flow->acs, $flowContent);
        $flowContent = str_replace('{blocker_bots}', $flow->blocker_bots, $flowContent);
        $flowContent = str_replace('{lock_isp}', strtoupper(implode(",", explode("\n", str_replace("\r", "", $flow->lock_isp)))), $flowContent);
        $flowContent = str_replace('{lock_referers}', strtoupper(implode(",", explode("\n", str_replace("\r", "", $flow->lock_referers)))), $flowContent);
        $flowContent = str_replace('{lock_device}', strtoupper(implode(',', $flow->lock_device)), $flowContent);
        $flowContent = str_replace('{lock_browser}', strtoupper(implode(',', $flow->lock_browser ?? [])), $flowContent);
        $flowContent = str_replace('{API_KEY}', Auth::user()->apikey ?? '', $flowContent);

        // Beautify code by removing excessive empty lines
        $flowContent = preg_replace("/(?:(?:\r\n|\r|\n)\s*){2,}/s", "\n\n", $flowContent);

        return response()->streamDownload(function () use ($flowContent) {
            echo $flowContent;
        }, 'index.php');
    }
}
