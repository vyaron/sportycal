var gCurrCtgAnimation = null;

function convertImageName(imgSrc) {
  var posDot = imgSrc.lastIndexOf(".");
  var imgNum = imgSrc.substr(posDot-1, 1);
  
  imgSrc = imgSrc.substr(0, posDot-1) + ((imgNum == '1')? '2' : '1') + imgSrc.substr(posDot);
  return imgSrc;
}

function animateCtg(e){
	e.stop();
	
	if (!gCurrCtgAnimation){
		var el = this;
		
		gCurrCtgAnimation = setInterval(function(){
			var rootCtgIcon = el.getElement('.rootCtgIcon');
			if (rootCtgIcon){
				
				var bgiStyle = rootCtgIcon.getStyle('background-image');
				var nextImage = convertImageName(bgiStyle);

				rootCtgIcon.setStyle('background-image', nextImage);
			}
		}, 400);
	}
}

function removeAnimateCtg(){
	if (gCurrCtgAnimation){
		clearInterval(gCurrCtgAnimation);
		gCurrCtgAnimation = null;
	}
}

window.addEvent('domready', function(){
	var rootCtgAnimates = $$('.rootCtgAnimate');
	rootCtgAnimates.each(function(el){
		el.addEvent('mouseover', animateCtg);
		el.addEvent('mouseout', removeAnimateCtg);
		
		
		var rootCtgIcon = el.getElement('.rootCtgIcon');
		if (rootCtgIcon){
			var bgiStyle = rootCtgIcon.getStyle('background-image');
			var posStart = bgiStyle.indexOf('(') + 1;
			var posEnd = bgiStyle.indexOf(')') - posStart;
			var imgSrc = bgiStyle.substr(posStart, posEnd);
			imgSrc = imgSrc.replace(/"/g, '');
			imgSrc = imgSrc.trim();
			preloadImage(convertImageName(imgSrc));
		}
	});
});
