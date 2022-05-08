const b64toBlob = (b64Data, contentType="", sliceSize=512) => {
  const byteCharacters = atob(b64Data);
  const byteArrays = [];

  for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
	const slice = byteCharacters.slice(offset, offset + sliceSize);

	const byteNumbers = new Array(slice.length);
	for (let i = 0; i < slice.length; i++) {
	  byteNumbers[i] = slice.charCodeAt(i);
	}

	const byteArray = new Uint8Array(byteNumbers);
	byteArrays.push(byteArray);
  }

  const blob = new Blob(byteArrays, {type: contentType});
  return blob;
};

function magazine_post_request(aPostIds, sTheme){
	jQuery.ajax( {
	   url: wpApiSettings.root + "magazine/v1/pdf",
	   method: "POST",
	   beforeSend: function ( xhr ) {
		   xhr.setRequestHeader( "X-WP-Nonce", wpApiSettings.nonce );
	   },
	   data:{
			"ids": aPostIds,
			"theme": sTheme,
			"base64": true
		},
		success: function (data){
			var blob = b64toBlob(data, "application/pdf");
			var link = document.createElement("a");
			var url  = window.URL.createObjectURL(blob);
			link.href=url;
			link.download="magazine.pdf";
			link.click();
			setTimeout(() => URL.revokeObjectURL(url), 2000);
		},
		error: function (data){
			alert("PDF generation, please try again.");        
		}
	});
}