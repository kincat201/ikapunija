function setRequired(exclude) {
	$('.cus_req').show();
	for(var i=0; i<exclude.length; i++) {
		$('#req_'+exclude[i]).hide();
	}
}

function modeModal(input) {
	$('#'+input['modalTitle']).html(input['mode']+' <?php echo $title; ?>');
	$('#'+input['modalSubmit']).data('type', input['mode']);

	if(input['mode'] == 'Delete')
	{
		$('.ves-custom').hide();
		$('#ves_delete').show();
		$('#'+input['modalSubmit']).data('type', input['mode']);
		$('#'+input['modalSubmit']).html('Delete');
	}

	else
	{ 
		excInput(input['exclude']); 
		setRequired(input['exclude_req']); 
		$('#'+input['modalSubmit']).html('Save');
	}
}

function submit(obj) {
	var type = $(obj).data('type');
	if(type == 'Add')
	{ addData(); }

	else if(type == 'Edit')
	{ editData(); }

	else if(type == 'Delete')
	{ deleteData(); }
}

function excInput(data) {
	$('.ves-custom').show();

	for( var i=0; i<data.length; i++)
	{ $('#ves_'+data[i]).hide(); }
}

function toBase64(element, obj) {
	var img = element.files[0];
	
	var reader = new FileReader();
	
	reader.onloadend = function() {
		$("#"+obj).data('images',reader.result);
		base64Img = reader.result.split(",");
		$("#"+obj).show();
	}
	reader.readAsDataURL(img);
}

function openImage(obj) {
	var image = new Image();
	image.src = $(obj).data('images');
	var w = window.open("");
	w.document.write(image.outerHTML);
} 

function setRequired(exclude) {
	$('.cus_req').show();
	for(var i=0; i<exclude.length; i++) {
		$('#req_'+exclude[i]).hide();
	}
}