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
	
	/* Create Loading Sprinner */
	var divLoading = document.createElement('div');
	divLoading.setAttribute('class', 'loading style-2');
	
	var divLoadingWheel = document.createElement('div');
	divLoadingWheel.setAttribute('class', 'loading-wheel');
	divLoading.appendChild(divLoadingWheel);
	
	var divLoadingText = document.createElement('div');
	divLoadingText.setAttribute('class', 'loading-text');
	divLoadingText.innerText = 'PDF rendering in progress, download starting soon!';
	divLoading.appendChild(divLoadingText);
	
	document.body.appendChild(divLoading);
	
	var styles = `
		.loading {
			width: 100%;
			height: 100%;
			position: fixed;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			background-color: rgba(0,0,0,.5);
		}
		.loading-wheel {
			width: 20px;
			height: 20px;
			margin-top: -40px;
			margin-left: -40px;
			
			position: absolute;
			top: 50%;
			left: 50%;
			
			border-width: 30px;
			border-radius: 50%;
			-webkit-animation: spin 1s linear infinite;
		}
		.style-2 .loading-wheel {
			border-style: double;
			border-color: lightseagreen transparent;
		}
		
		.loading-text{
			font-weight:bold;
			font-size:26px;
			color:lightseagreen;
			
			position: absolute;
			top: 50%;
			left: 50%;
			
			margin-top:80px;
			transform:translateX(-50%);
		}
		
		@-webkit-keyframes spin {
			0% {
				-webkit-transform: rotate(0);
			}
			100% {
				-webkit-transform: rotate(-360deg);
			}
		}
	`;

	var styleSheet = document.createElement("style")
	styleSheet.innerText = styles
	document.head.appendChild(styleSheet)
	
	
	/* Send Request */
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
	
			/* Remove Loading Spinner */
			divLoading.remove();
		},
		error: function (data){
			alert("PDF generation, please try again."); 
	
			/* Remove Loading Spinner */
			divLoading.remove();       
		}
	});
}