var thisScript = document.currentScript;
var buttonId = thisScript.src;
	buttonId = buttonId.split("?");
	buttonId = buttonId[1];
var ButtonCode = $("#"+buttonId).attr('dataid');
var datapoint = $("#"+buttonId).attr('datapoint');
var siteUrl = "admin"; 



$.getJSON("https://new-actionbutton.voxara.net/public/index.php/projects/widgets/data/"+ButtonCode+"?callback=?&datapoints="+datapoint+"&buttonId="+buttonId+"&CustomerUrl="+siteUrl, function(result){
	
	
	var id = result.data;
	var data = result.number;
	if(data == "false"){
		 
		$("#"+id).parent().parent().html("<h3>Security Warning: customer URL not matched.</h3>");
	}
	else {
		$("#"+id).text(data);
		const obj = document.getElementById(id);
		animateValue(obj, 0, data, 3000);
	}
});



function animateValue(obj, start, end, duration) {
  let startTimestamp = null;
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    obj.innerHTML = Math.floor(progress * (end - start) + start);
    if (progress < 1) {
      window.requestAnimationFrame(step);
    }
  };
  window.requestAnimationFrame(step);
}