!function(e){"use strict";var a,t,n;localStorage.getItem("minia-language");function o(){var t=document.querySelectorAll(".counter-value");t.forEach(function(o){!function t(){var e=+o.getAttribute("data-target"),a=+o.innerText,n=e/250;n<1&&(n=1),a<e?(o.innerText=(a+n).toFixed(0),setTimeout(t,1)):o.innerText=e}()})}function r(){for(var t=document.getElementById("topnav-menu-content").getElementsByTagName("a"),e=0,a=t.length;e<a;e++)t[e]&&t[e].parentElement&&"nav-item dropdown active"===t[e].parentElement.getAttribute("class")&&(t[e].parentElement.classList.remove("active"),t[e].nextElementSibling&&t[e].nextElementSibling.classList.remove("show"))}function d(t){document.getElementById(t).checked=!0}function i(){document.webkitIsFullScreen||document.mozFullScreen||document.msFullscreenElement||e("body").removeClass("fullscreen-enable")}if(e("#side-menu").metisMenu(),o(),a=document.body.getAttribute("data-sidebar-size"),e(window).on("load",function(){e(".switch").on("switch-change",function(){toggleWeather()}),1024<=window.innerWidth&&window.innerWidth<=1366&&(document.body.setAttribute("data-sidebar-size","sm"),d("sidebar-size-small"))}),e("#vertical-menu-btn").on("click",function(t){t.preventDefault(),e("body").toggleClass("sidebar-enable"),992<=e(window).width()&&(null==a?null==document.body.getAttribute("data-sidebar-size")||"lg"==document.body.getAttribute("data-sidebar-size")?document.body.setAttribute("data-sidebar-size","sm"):document.body.setAttribute("data-sidebar-size","lg"):"md"==a?"md"==document.body.getAttribute("data-sidebar-size")?document.body.setAttribute("data-sidebar-size","sm"):document.body.setAttribute("data-sidebar-size","md"):"sm"==document.body.getAttribute("data-sidebar-size")?document.body.setAttribute("data-sidebar-size","lg"):document.body.setAttribute("data-sidebar-size","sm"))}),e("#sidebar-menu a").each(function(){var t=window.location.href.split(/[?#]/)[0];this.href==t&&(e(this).addClass("active"),e(this).parent().addClass("mm-active"),e(this).parent().parent().addClass("mm-show"),e(this).parent().parent().prev().addClass("mm-active"),e(this).parent().parent().parent().addClass("mm-active"),e(this).parent().parent().parent().parent().addClass("mm-show"),e(this).parent().parent().parent().parent().parent().addClass("mm-active"))}),e(document).ready(function(){var t;0<e("#sidebar-menu").length&&0<e("#sidebar-menu .mm-active .active").length&&(300<(t=e("#sidebar-menu .mm-active .active").offset().top)&&(t-=300,e(".vertical-menu .simplebar-content-wrapper").animate({scrollTop:t},"slow")))}),e(".navbar-nav a").each(function(){var t=window.location.href.split(/[?#]/)[0];this.href==t&&(e(this).addClass("active"),e(this).parent().addClass("active"),e(this).parent().parent().addClass("active"),e(this).parent().parent().parent().addClass("active"),e(this).parent().parent().parent().parent().addClass("active"),e(this).parent().parent().parent().parent().parent().addClass("active"),e(this).parent().parent().parent().parent().parent().parent().addClass("active"))}),e('[data-toggle="fullscreen"]').on("click",function(t){t.preventDefault(),e("body").toggleClass("fullscreen-enable"),document.fullscreenElement||document.mozFullScreenElement||document.webkitFullscreenElement?document.cancelFullScreen?document.cancelFullScreen():document.mozCancelFullScreen?document.mozCancelFullScreen():document.webkitCancelFullScreen&&document.webkitCancelFullScreen():document.documentElement.requestFullscreen?document.documentElement.requestFullscreen():document.documentElement.mozRequestFullScreen?document.documentElement.mozRequestFullScreen():document.documentElement.webkitRequestFullscreen&&document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT)}),document.addEventListener("fullscreenchange",i),document.addEventListener("webkitfullscreenchange",i),document.addEventListener("mozfullscreenchange",i),document.getElementById("topnav-menu-content")){for(var l=document.getElementById("topnav-menu-content").getElementsByTagName("a"),s=0,u=l.length;s<u;s++)l[s].onclick=function(t){t&&t.target&&"#"===t.target.getAttribute("href")&&(t.target.parentElement.classList.toggle("active"),t.target.nextElementSibling&&t.target.nextElementSibling.classList.toggle("show"))};window.addEventListener("resize",r)}[].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function(t){return new bootstrap.Tooltip(t)}),[].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')).map(function(t){return new bootstrap.Popover(t)}),[].slice.call(document.querySelectorAll(".toast")).map(function(t){return new bootstrap.Toast(t)}),window.sessionStorage&&((t=sessionStorage.getItem("is_visited"))?e("#"+t).prop("checked",!0):sessionStorage.setItem("is_visited","layout-ltr")),e(window).on("load",function(){e("#status").fadeOut(),e("#preloader").delay(350).fadeOut("slow")}),n=document.getElementsByTagName("body")[0],e(".right-bar-toggle").on("click",function(t){e("body").toggleClass("right-bar-enabled")}),e("#mode-setting-btn").on("click",function(t){n.hasAttribute("data-layout-mode")&&"dark"==n.getAttribute("data-layout-mode")?(document.body.setAttribute("data-layout-mode","light"),document.body.setAttribute("data-topbar","light"),document.body.setAttribute("data-sidebar","light"),n.hasAttribute("data-layout")&&"horizontal"==n.getAttribute("data-layout")||document.body.setAttribute("data-sidebar","light"),d("topbar-color-light"),d("sidebar-color-light"),d("topbar-color-light")):(document.body.setAttribute("data-layout-mode","dark"),document.body.setAttribute("data-topbar","dark"),document.body.setAttribute("data-sidebar","dark"),n.hasAttribute("data-layout")&&"horizontal"==n.getAttribute("data-layout")||document.body.setAttribute("data-sidebar","dark"),d("layout-mode-dark"),d("sidebar-color-dark"),d("topbar-color-dark"))}),e(document).on("click","body",function(t){0<e(t.target).closest(".right-bar-toggle, .right-bar").length||e("body").removeClass("right-bar-enabled")}),n.hasAttribute("data-layout")&&"horizontal"==n.getAttribute("data-layout")?(d("layout-horizontal"),e(".sidebar-setting").hide()):d("layout-vertical"),n.hasAttribute("data-layout-mode")&&"dark"==n.getAttribute("data-layout-mode")?d("layout-mode-dark"):d("layout-mode-light"),n.hasAttribute("data-layout-size")&&"boxed"==n.getAttribute("data-layout-size")?d("layout-width-boxed"):d("layout-width-fuild"),n.hasAttribute("data-layout-scrollable")&&"true"==n.getAttribute("data-layout-scrollable")?d("layout-position-scrollable"):d("layout-position-fixed"),n.hasAttribute("data-topbar")&&"dark"==n.getAttribute("data-topbar")?d("topbar-color-dark"):d("topbar-color-light"),n.hasAttribute("data-sidebar-size")&&"sm"==n.getAttribute("data-sidebar-size")?d("sidebar-size-small"):n.hasAttribute("data-sidebar-size")&&"md"==n.getAttribute("data-sidebar-size")?d("sidebar-size-compact"):d("sidebar-size-default"),n.hasAttribute("data-sidebar")&&"brand"==n.getAttribute("data-sidebar")?d("sidebar-color-brand"):n.hasAttribute("data-sidebar")&&"dark"==n.getAttribute("data-sidebar")?d("sidebar-color-dark"):d("sidebar-color-light"),document.getElementsByTagName("html")[0].hasAttribute("dir")&&"rtl"==document.getElementsByTagName("html")[0].getAttribute("dir")?d("layout-direction-rtl"):d("layout-direction-ltr"),e("input[name='layout']").on("change",function(){window.location.href="vertical"==e(this).val()?"/":"layouts-horizontal"}),e("input[name='layout-mode']").on("change",function(){"light"==e(this).val()?(document.body.setAttribute("data-layout-mode","light"),document.body.setAttribute("data-topbar","light"),document.body.setAttribute("data-sidebar","light"),n.hasAttribute("data-layout")&&"horizontal"==n.getAttribute("data-layout")||document.body.setAttribute("data-sidebar","light"),d("topbar-color-light"),d("sidebar-color-light")):(document.body.setAttribute("data-layout-mode","dark"),document.body.setAttribute("data-topbar","dark"),document.body.setAttribute("data-sidebar","dark"),n.hasAttribute("data-layout")&&"horizontal"==n.getAttribute("data-layout")||document.body.setAttribute("data-sidebar","dark"),d("topbar-color-dark"),d("sidebar-color-dark"))}),e("input[name='layout-direction']").on("change",function(){"ltr"==e(this).val()?(document.getElementsByTagName("html")[0].removeAttribute("dir"),document.getElementById("bootstrap-style").setAttribute("href","/css/bootstrap.min.css"),document.getElementById("app-style").setAttribute("href","/css/app.min.css")):(document.getElementById("bootstrap-style").setAttribute("href","/css/bootstrap-rtl.min.css"),document.getElementById("app-style").setAttribute("href","/css/app-rtl.min.css"),document.getElementsByTagName("html")[0].setAttribute("dir","rtl"))}),Waves.init(),e("#checkAll").on("change",function(){e(".table-check .form-check-input").prop("checked",e(this).prop("checked"))}),e(".table-check .form-check-input").change(function(){e(".table-check .form-check-input:checked").length==e(".table-check .form-check-input").length?e("#checkAll").prop("checked",!0):e("#checkAll").prop("checked",!1)})}(jQuery),feather.replace();