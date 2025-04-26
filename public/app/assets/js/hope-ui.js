/*
* Version: 1.2.0
* Template: Hope-Ui - Responsive Bootstrap 5 Admin Dashboard Template
* Author: iqonic.design
* Design and Developed by: iqonic.design
* NOTE: This file contains the script for initialize & listener Template.
*/

/*----------------------------------------------
Index Of Script
------------------------------------------------

------- Plugin Init --------

:: Sticky-Nav
:: Popover
:: Tooltip
:: Circle Progress
:: Progress Bar
:: NoUiSlider
:: CopyToClipboard
:: CounterUp 2
:: SliderTab
:: Data Tables
:: Active Class for Pricing Table
:: AOS Animation Plugin

------ Functions --------

:: Resize Plugins
:: Loader Init
:: Sidebar Toggle
:: Back To Top

------- Listners ---------

:: DOMContentLoaded
:: Window Resize
:: DropDown
:: Form Validation
:: Flatpickr
------------------------------------------------
Index Of Script
----------------------------------------------*/
"use strict";
/*---------------------------------------------------------------------
              Sticky-Nav
-----------------------------------------------------------------------*/
window.addEventListener('scroll', function () {
  let yOffset = document.documentElement.scrollTop;
  let navbar = document.querySelector(".navs-sticky")
  if (navbar !== null) {
    if (yOffset >= 100) {
      navbar.classList.add("menu-sticky");
    } else {
      navbar.classList.remove("menu-sticky");
    }
  }
});

/*---------------------------------------------------------------------
                 noUiSlider
-----------------------------------------------------------------------*/
const rangeSlider = document.querySelectorAll('.range-slider');
Array.from(rangeSlider, (elem) => {
  if (typeof noUiSlider !== typeof undefined) {
    noUiSlider.create(elem, {
      start: [20, 80],
      connect: true,
      range: {
        'min': 0,
        'max': 100
      }
    })
  }
})

const slider = document.querySelectorAll('.slider');
Array.from(slider, (elem) => {
  if (typeof noUiSlider !== typeof undefined) {
    noUiSlider.create(elem, {
      start: 50,
      connect: [true, false],
      range: {
        'min': 0,
        'max': 100
      }
    })
  }
})
/*---------------------------------------------------------------------
              Copy To Clipboard
-----------------------------------------------------------------------*/
const copy = document.querySelectorAll('[data-toggle="copy"]')
if (typeof copy !== typeof undefined) {
  Array.from(copy, (elem) => {
    elem.addEventListener('click', (e) => {
      const target = elem.getAttribute("data-copy-target");
      let value = elem.getAttribute("data-copy-value");
      const container = document.querySelector(target);
      if (container !== undefined && container !== null) {
        if (container.value !== undefined && container.value !== null) {
          value = container.value;
        } else {
          value = container.innerHTML;
        }
      }
      if (value !== null) {
        const elem = document.createElement("input");
        document.querySelector("body").appendChild(elem);
        elem.value = value;
        elem.select();
        document.execCommand("copy");
        elem.remove();
      }
    })
  });
}


/*---------------------------------------------------------------------
              SliderTab
-----------------------------------------------------------------------*/
Array.from(document.querySelectorAll('[data-toggle="slider-tab"]'), (elem) => {
  if (typeof SliderTab !== typeof undefined) {
    new SliderTab(elem)
  }
})

let Scrollbar
if (typeof Scrollbar !== typeof null) {
  if (document.querySelectorAll(".data-scrollbar").length) {
    Scrollbar = window.Scrollbar
    Scrollbar.init(document.querySelector('.data-scrollbar'), {
      continuousScrolling: false,
    })
  }
}
/*---------------------------------------------------------------------
  Data tables
-----------------------------------------------------------------------*/
if ($.fn.DataTable) {
  if ($('[data-toggle="data-table"]').length) {
    const table = $('[data-toggle="data-table"]').DataTable({
      "dom": '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive border-bottom my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
    });
  }
}

/*---------------------------------------------------------------------
              Resize Plugins
-----------------------------------------------------------------------*/
const resizePlugins = () => {
  // sidebar-mini
  const tabs = document.querySelectorAll('.nav')
  const sidebarResponsive = document.querySelector('.sidebar-default')
  if (window.innerWidth < 1025) {
    Array.from(tabs, (elem) => {
      if (!elem.classList.contains('flex-column') && elem.classList.contains('nav-tabs') && elem.classList.contains('nav-pills')) {
        elem.classList.add('flex-column', 'on-resize');
      }
    })
    if (sidebarResponsive !== null) {
      if (!sidebarResponsive.classList.contains('sidebar-mini')) {
        sidebarResponsive.classList.add('sidebar-mini', 'on-resize')
      }
    }
  } else {
    Array.from(tabs, (elem) => {
      if (elem.classList.contains('on-resize')) {
        elem.classList.remove('flex-column', 'on-resize');
      }
    })
    if (sidebarResponsive !== null) {
      if (sidebarResponsive.classList.contains('sidebar-mini') && sidebarResponsive.classList.contains('on-resize')) {
        sidebarResponsive.classList.remove('sidebar-mini', 'on-resize')
      }
    }
  }
}

/*---------------------------------------------------------------------
              Sidebar Toggle
-----------------------------------------------------------------------*/
const sidebarToggle = (elem) => {
  elem.addEventListener('click', (e) => {
    const sidebar = document.querySelector('.sidebar')
    if (sidebar.classList.contains('sidebar-mini')) {
      sidebar.classList.remove('sidebar-mini')
    } else {
      sidebar.classList.add('sidebar-mini')
    }
  })
}

const sidebarToggleBtn = document.querySelectorAll('[data-toggle="sidebar"]')
const sidebar = document.querySelector('.sidebar-default')
if (sidebar !== null) {
  const sidebarActiveItem = sidebar.querySelectorAll('.active')
  Array.from(sidebarActiveItem, (elem) => {
    if (!elem.closest('ul').classList.contains('iq-main-menu')) {
      const childMenu = elem.closest('ul')
      childMenu.classList.add('show')
      const parentMenu = childMenu.closest('li').querySelector('.nav-link')
      parentMenu.classList.add('collapsed')
      parentMenu.setAttribute('aria-expanded', true)
    }
  })
}
Array.from(sidebarToggleBtn, (sidebarBtn) => {
  sidebarToggle(sidebarBtn)
})
/*---------------------------------------------------------------------------
                            Back To Top
----------------------------------------------------------------------------*/
const backToTop = document.getElementById("back-to-top")
if (backToTop !== null && backToTop !== undefined) {
  document.getElementById("back-to-top").classList.add("animate__animated", "animate__fadeOut")
  window.addEventListener('scroll', (e) => {
    if (document.documentElement.scrollTop > 250) {
      document.getElementById("back-to-top").classList.remove("animate__fadeOut")
      document.getElementById("back-to-top").classList.add("animate__fadeIn")
    } else {
      document.getElementById("back-to-top").classList.remove("animate__fadeIn")
      document.getElementById("back-to-top").classList.add("animate__fadeOut")
    }
  })
  // scroll body to 0px on click
  document.querySelector('#top').addEventListener('click', (e) => {
    e.preventDefault()
    window.scrollTo({ top: 0, behavior: 'smooth' });
  })
}
/*---------------------------------------------------------------------
              DOMContentLoaded
-----------------------------------------------------------------------*/
document.addEventListener('DOMContentLoaded', (event) => {
  resizePlugins()
});
/*---------------------------------------------------------------------
              Window Resize
-----------------------------------------------------------------------*/
window.addEventListener('resize', function (event) {
  resizePlugins()
});
/*---------------------------------------------------------------------
| | | | | DropDown
-----------------------------------------------------------------------*/
function darken_screen(yesno) {
  if (yesno == true) {
    if (document.querySelector('.screen-darken') !== null) {
      document.querySelector('.screen-darken').classList.add('active');
    }
  }
  else if (yesno == false) {
    if (document.querySelector('.screen-darken') !== null) {
      document.querySelector('.screen-darken').classList.remove('active');
    }
  }
}
function close_offcanvas() {
  darken_screen(false);
  if (document.querySelector('.mobile-offcanvas.show') !== null) {
    document.querySelector('.mobile-offcanvas.show').classList.remove('show');
    document.body.classList.remove('offcanvas-active');
  }
}
function show_offcanvas(offcanvas_id) {
  darken_screen(true);
  if (document.getElementById(offcanvas_id) !== null) {
    document.getElementById(offcanvas_id).classList.add('show');
    document.body.classList.add('offcanvas-active');
  }
}
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll('[data-trigger]').forEach(function (everyelement) {
    let offcanvas_id = everyelement.getAttribute('data-trigger');
    everyelement.addEventListener('click', function (e) {
      e.preventDefault();
      show_offcanvas(offcanvas_id);
    });
  });
  if (document.querySelectorAll('.btn-close')) {
    document.querySelectorAll('.btn-close').forEach(function (everybutton) {
      everybutton.addEventListener('click', function (e) {
        close_offcanvas();
      });
    });
  }
  if (document.querySelector('.screen-darken')) {
    document.querySelector('.screen-darken').addEventListener('click', function (event) {
      close_offcanvas();
    });
  }
});
if (document.querySelector('#navbarSideCollapse')) {
  document.querySelector('#navbarSideCollapse').addEventListener('click', function () {
    document.querySelector('.offcanvas-collapse').classList.toggle('open')
  })
}
/*---------------------------------------------------------------------
                                   Form Validation
-----------------------------------------------------------------------*/
// Example starter JavaScript for disabling form submissions if there are invalid fields
window.addEventListener('load', function () {
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.getElementsByClassName('needs-validation');
  // Loop over them and prevent submission
  var validation = Array.prototype.filter.call(forms, function (form) {
    form.addEventListener('submit', function (event) {
      if (form.checkValidity() === false) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  });
}, false);

(function () {

  
  
})();