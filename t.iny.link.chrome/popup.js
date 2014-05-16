var linkGenerator_ = {
    x_: function() {
        chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, 
            function (tabs) {
                var node = document.getElementById('t.iny.link');
                var lx='http://t.iny.link/';
                var params = 'url='+tabs[0].url;
                var req = new XMLHttpRequest();
                
                req.open("POST", lx, true); 

                //Send the proper header information along with the request
                req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                req.setRequestHeader("Content-length", params.length);
                req.setRequestHeader("Connection", "close");
                
                req.onload = function() {
                        node.innerHTML=lx+' '+req.responseText;
                        };
                req.send(params);
            });
    }
};

document.addEventListener('DOMContentLoaded', function () {
    linkGenerator_.x_();
});
