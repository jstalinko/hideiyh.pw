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
        $props['packages'] = Package::where('active',true)->orderBy('price','asc')->get();
        $props['testimonials'] = Testimonial::where('active',true)->get();
        $data['props'] = $props;
        return Inertia::render('justorange-default', $data);
    }
    public function plan(Request $request)
    {
        if(auth()->check())
        {
            $user = User::find(auth()->user()->id);
            $customer = [
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'auth' => true
            ];
        }else{
            $customer = [
                'name' => '',
                'phone' => '',
                'email' => '',
                'auth' => false
            ];
        };
        $plan_name = $request->plan_name;
        $package = Package::where('name','LIKE','%'.$plan_name.'%')->first();
        $props['packageActive'] = $package;
        $props['packages'] = Package::where('active',true)->get();
        $props['customerDetails'] = $customer;
        $data['props'] = $props;
        return Inertia::render('plan',$data);
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
        if($request->type == 'all')
        {
          
            $download = Download::select('version','tag_name','changelog','id')->orderBy('version','desc')->get();
            return response()->json(['success' => true, 'data' => ['release' => $download ]],200,[],JSON_PRETTY_PRINT);
        }elseif($request->type == 'latest')
            {
                
            $download = Download::select('version','tag_name','changelog','id')->orderBy('version','desc')->first();
             return response()->json(['success' => true, 'data' => ['release' => $download]],200,[],JSON_PRETTY_PRINT);

            }
        }
        public function adiyh_store(Request $request)
        {

            // check user-agent is Adiyh-Plugin
            // $userAgent = $request->header('User-Agent');
            // if (strpos($userAgent, 'Adiyh-Plugin') === false) {
            //     return response()->json(['error' => 'Unauthorized'], 401);
            // }
            $products = Store::orderBy('id','desc')->get()->map(function ($product) {
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
            return Inertia::render('plan-invoice',$data);
        }
    
}
