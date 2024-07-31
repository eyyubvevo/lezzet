const hamburger = document.querySelector('.hamburger')
const navigation = document.querySelector('.navigation')
const closeNavigation = document.querySelector('.navigation-close')

hamburger.addEventListener('click', function() {
  navigation.style.right = "0"
})


closeNavigation.addEventListener('click' , function() {
    navigation.style.right = "-500px"
})


var dropdownToggle = document.getElementsByClassName("dropdown-toggle");
var i;

for (i = 0; i < dropdownToggle.length; i++) {
dropdownToggle[i].addEventListener("click", function() {
  this.classList.toggle("active");
  var dropdownContent = this.nextElementSibling;
  if (dropdownContent.style.display === "block") {
    dropdownContent.style.display = "none";
  } else {
    dropdownContent.style.display = "block";
  }
});
}




const btns = document.querySelectorAll(".acc-btn");

function accordion() {
  this.classList.toggle("is-open");

  const content = this.nextElementSibling;

  if (content.style.maxHeight) content.style.maxHeight = null;
  else content.style.maxHeight = content.scrollHeight + "px";
}

btns.forEach((el) => el.addEventListener("click", accordion));

  	// Scroll Top

    $(window).scroll(function () {
      var scroll = $(window).scrollTop();
      if (scroll >= 100) {
        $("#toTop").fadeIn();
      } else {
        $("#toTop").fadeOut();
      }
      });

      $(document).on("click", "#toTop", function () {
      $("html, body").animate({ scrollTop: 0 }, 500);
      });



      function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
      }

      function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdown");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
          txtValue = a[i].textContent || a[i].innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            a[i].style.display = "";
          } else {
            a[i].style.display = "none";
          }
        }
      }




        var header = $('.sticky-bar');
        var $window = $(window);
        $window.on('scroll', function() {
            var scroll = $window.scrollTop();
            if (scroll < 50) {
              // console.log('ss');
                header.removeClass('stick');
            } else {
                header.addClass('stick');
            }
        });





// let minValue = document.getElementById("min-value");
// let maxValue = document.getElementById("max-value");

// const rangeFill = document.querySelector(".range-fill");

// // Function to validate range and update the fill color on slider
// function validateRange() {
//     console.log(parseInt(inputElements[0].value), 'o');
//   let minPrice = parseInt(inputElements[0].value);
//   let maxPrice = parseInt(inputElements[1].value);

//   if (minPrice > maxPrice) {
//     let tempValue = maxPrice;
//     maxPrice = minPrice;
//     minPrice = tempValue;
//   }

//   const minPercentage = ((minPrice - 10) / 490) * 100;
//   const maxPercentage = ((maxPrice - 10) / 490) * 100;

//   rangeFill.style.left = minPercentage + "%";
//   rangeFill.style.width = maxPercentage - minPercentage + "%";

//   minValue.innerHTML = "₼" + minPrice;
//   maxValue.innerHTML = "₼" + maxPrice;
// }



// Add an event listener to each input element
inputElements.forEach((element) => {
  element.addEventListener("input", validateRange);
});

// Initial call to validateRange
validateRange();



document.addEventListener("click", function(e) {
  var searchResultDiv = document.getElementById("myDropdown");
  var searchInput = document.querySelector(".dropbtn-search");

  if (e.target === searchResultDiv || searchResultDiv.contains(e.target)) {
      return;
  }

  if (e.target === searchInput || searchInput.contains(e.target)) {
      searchResultDiv.style.display = "block";
  } else {
      searchResultDiv.style.display = "none";
  }
});
