@extends('layouts.app')

@section('title', 'Product Set')

@section('sub_title', 'manage your slider')

@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif
        @if($errors->any())
            <div class="col-md-12">
                <div class="callout callout-danger">
                    <h4>Warning!</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="col-md-12" id="signupForm">
            <?php
            if(!empty($productSet->id)){
                $product = \App\Models\Product::where('product_set_id', $productSet->id)->first(); 
            }
            
            ?>
            <div class="tab mb-5" style="margin:0 0 6px 0;">
                @if (!empty($productSet->id))
                <a href="?tab=basic" class="btn btn-sm btn-success">Basic</a>
                <a href="?tab=fabric" class="btn btn-sm btn-success">Fabric</a>
                <a href="?tab=info" class="btn btn-sm btn-success">Info</a>
                @if ($product != null)
                <a href="{{ url('edit_product/'.$product->id.'?p=links') }}" class="btn btn-sm btn-success">Links</a>
                @endif
                
                @endif

            </div>
            @includeIf($tab)

        </div>

    </div>
@endsection

@push('scripts')
<link rel="stylesheet" href="{{asset('public/cssc/select2/dist/css/select2.min.css')}}">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>

let productSearchTimeout = setTimeout(null,1500);

function productSetProductSearch(self){
    clearTimeout(productSearchTimeout)
    productSearchTimeout = setTimeout(function(){
        productSearchCallAPI(self.value)
    },1500)
}

function productSearchCallAPI(keyword){
    console.log(keyword,'get called')

    axios.get(`{{ route("admin.product_set.product.search") }}`, {
    params: {
      keyword: keyword
    }
  })
  .then(function (response) {
    const tablebody_for_product = document.querySelector('#product-results');

      if(response){
        tablebody_for_product.style.display = '';
      }

      let productHTML = ""
    response.data.products.forEach((item,index) => {

        productHTML += `<tr><td width="30px">
                                        <label class="checkbox-container">
                                            <input type="checkbox" name="products[]" value="${item.id}"  id="isvalue">
                                            <span class="checkmark"></span>
                                        </label>
                                    </td>
                                    <td width="90px"><img src="{{ url('/') }}/${item.first_image}" alt="" width="80px;"></td>
                                    <td>

                                        <strong>${item.title}</strong><br/>
                                        <span>${item.sub_title}</span><br/>
                                        <span>
                                            Product Code: <strong>${item.product_code}</strong><br/>
                                        </span>
                                        <span>
                                            Price :  <strong>৳. ${item.price_now}</strong>
                                        </span>

                                    </td>
                                    <td width="80em">
                                                        <input type="number" id="qty+${item.id}" name="qty[${item.id}]" class="form-control" value="1" onchange="setProductSetQty(${item.id})">
                                     </td>
                        </tr>`
    })

    document.getElementById("product-search-results").innerHTML = productHTML
  })
  .catch(function (error) {
    console.log(error);
  })
  .then(function () {
    // always executed
  });

}



</script>

<style>



    .image-section {
        padding: .4rem;
        border-top: 1px solid #ddd;
        padding-top: 1.4rem;
    }
    #modal_button{
        display:flex;
        float: right;
        border-radius: .5rem;
        margin-bottom: .3rem;
    }

    #td-edit-delete {
        text-align: center;
    }
    </style>
@endpush


