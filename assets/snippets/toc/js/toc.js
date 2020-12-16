document.addEventListener('DOMContentLoaded', function(){
    var sideBar = document.querySelector('#leftSidebar'),
        sideBarBtn = document.querySelector('#leftSidebarToggler');
    upBtn = document.querySelector('#upBtn');
    windowHeight = document.body.scrollHeight;


    window.addEventListener('scroll', function(e){
        var scrollPosition = window.pageYOffset;
        var diff = parseInt((scrollPosition * 100) / windowHeight);


        if(diff >= 10){
            upBtn.classList.add('active')
            sideBar.classList.add('visible')
        }else{
            upBtn.classList.remove('active')
            sideBar.classList.remove('visible')
        }
    })

    upBtn.onclick = function () {
        window.scroll({top: 0, behavior: 'smooth'});
    }

    document.body.onclick = function(e){
        if((e.target === sideBarBtn && sideBar.classList.contains('active')) || !sideBar.contains(e.target)){
            sideBar.classList.remove('active')
        }else{
            sideBar.classList.add('active')
        }
    }
});