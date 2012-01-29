window.onload = function(){
	S.setDir();
	
	$('#recurse').click(function(e){ S.recurseFlatten(); e.preventDefault(); });
	$('#zipit').click(function(e){ S.zipit(); e.preventDefault(); });
	
	$('#dir').change(function(){ S.setDir(); });
	
	$('#warp').toggle( function(e){
		$('body').css('background', "#ccc url(images/warp.gif) 0 0 repeat");
		$(this).text('[ Normal Mode ]');
	}, function(e){
		$('body').css('background', "#ccc");
		$(this).text('( Warp Mode )');
	});
	
	$('#emailForm').submit( function(e){
		var data= $(this).serialize();
		$.ajax({
			url: 'inc/sendZip.php',
			type: 'post',
			data: data,
			dataType: 'json',
			success: function(json){
				
			}
		});
		return false;
	});
}


S= {
	action: function(url, type, data, dataType, fn){
		$.ajax({
			url: url,
			type: type,
			dataType: dataType,
			success: fn
		});
	},
	recurseFlatten: function(){
		$.ajax({
			url: 'inc/recurse.php',
			type: 'post',
			data: {'dir': $('#dir').val(), 'utc' : S.unix_tstamp()},
			dataType: 'text',
			beforeSend: function(){ $('#result').empty().text('flattening files. . .').slideDown(); },
			success: function(msg){
				if(msg == '0'){
					$('#result').empty().text("Oops. Your project folder is not writable..").slideDown(200);
				}else{
					$('#result').empty().text('Php files rendered/updated as html files in main project directory.').slideDown(200);
					S.setDir();
				}
				
			}
		});
	},
	setDir: function(){

		var proj = $('#dir').val();
			$.ajax({
				url:'inc/fileList.php', 
				type: 'post',
				data: {'dir': proj, 'filetype': 'html'}, 
				dataType: 'json',
				beforeSend: function(){ $('#fileList').text('loading..');},
				success: function(data){
					if(data == ''){
						$('#fileList').text('There are no html files in this directory..');
					}else{
						$('.listBox').empty();
						$.each(data, function(i, item){
							if(item.type == 'html'){
								$('#fileList').append('<a target="_blank" href="'+ item.path +'">' + item.file + '</a>');
							}else if(item.type == 'zip'){
								$('#zipList').append('<a class="emailBubble" href="#emailForm">' + item.file + '</a>');
							}
							
						});
						$('.emailBubble').click(S.injectVals).fancybox(overlayOpts);
					}
			}	
		});
	},
	setZipDir: function(){

		var proj = $('#dir').val();
			$.ajax({
				url:'inc/fileList.php', 
				type: 'post',
				data: {'dir': proj, 'filetype': 'html'}, 
				dataType: 'json',
				beforeSend: function(){ $('#zipList').text('zipping..');},
				success: function(data){
					if(data == ''){
						$('#zipList').text('There are no html files in this directory..');
					}else{
						$('#zipList').empty();
						$.each(data, function(i, item){
							if(item.type == 'zip'){
								//var link = '<a href="mailto:Enter Email?subject='+item.file+'&body=<a href="'+item.path+'">'+item.file+'</a>"'
								$('#zipList').append('<a class="emailBubble" href="#emailForm">' + item.file + '</a>');
							}
							
						});
						$('.emailBubble').click(S.injectVals).fancybox(overlayOpts);
					}
			}	
		});
	},
	injectVals: function(){
		$('#dirSend').val($('#dir').val());
		$('#zip').val($(this).html());
		$('#zipName').html($(this).html());
	},
	zipit: function(){
		var proj = $('#dir').val();
		if($('#withPhp:checked').length){
			var withPhp = 1;
		}else{
			var withPhp = 0;
		}
		$.ajax({
			url:'inc/zipper.php', 
			type: 'post',
			data: {'dir': proj, 'withPhp': withPhp}, 
			dataType: 'json',
			beforeSend: function(){ $('#zipList').text('archiving..');},
			success: function(data){
				if(data.number > 0){
					S.setZipDir();
				}
			}
		});
	},
	unix_tstamp: function(){
		return parseInt(new Date().getTime().toString().substring(0, 10))
	}
}

var overlayOpts = {
		//onStart: S.injectVals
}
