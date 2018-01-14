function getDeliveryProductsFunc(obj, url)
{
	if(obj.offers.length < 1)
	{
		var idProduct = obj.product.id;
	}
	else {
		var idProduct = obj.offers[obj.offerNum]['ID'];
	}

	var productQuanity = obj.obQuantity.value;

	if(!productQuanity || !idProduct) {
		return;
	}

	var data = {
		idProduct : idProduct,
		productQuanity : productQuanity
	}
	BX.ready(function(){
		BX.ajax({
			method: 'POST',
			data: data,
			dataType: 'html',
			url: url,
			async: true,
			onsuccess: function(data){
				$('#product_delivery_box').html(data);
			}
		});
	});

}