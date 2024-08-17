@if(\App\Models\Company::count() > 0)
    @foreach(\App\Models\Company::all() as $company)
        <div id="myModal" class="modal-img">
            <!-- Modal content -->
            <div class="modal-img-content">
                <span class="modal-img-close" onclick="closeModal()">&times;</span>
                <img src='{{asset('storage/'.$company->image)}}' alt="lezzet al">
            </div>
        </div>
        @endforeach
        @endif
        </main>

        {!! Meta::footer()->toHtml() !!}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
                crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"
                integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg=="
                crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(document).ready(function () {
                $('.site-slider').slick({
                    dots: false,
                    autoplay: true,
                    autoplaySpeed: 3000,
                    infinite: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    centerMode: true,
                    centerPadding: '0',
                });

            });
        </script>

        <script src="{{asset('frontend/assest/js/main.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <script>
            var swiper = new Swiper(".mySwiper", {
                loop: true,
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
            });
            var swiper2 = new Swiper(".mySwiper2", {
                loop: true,
                spaceBetween: 10,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                thumbs: {
                    swiper: swiper,
                },
            });

               // Open the Modal
            function openModal() {
                document.getElementById("galery-myModal").style.display = "block";
            }

            // Close the Modal
            function closeModal() {
                document.getElementById("galery-myModal").style.display = "none";
            }

            window.onclick = function(event) {
                var modal = document.getElementById("galery-myModal");
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            var slideIndex = 1;
            showSlides(slideIndex);

            // Next/previous controls
            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            // Thumbnail image controls
            function currentSlide(n) {
                showSlides(slideIndex = n);
            }


            function showSlides(n) {
                var i;
                var slides = document.getElementsByClassName("galery-mySlides");
                var dots = document.getElementsByClassName("galery-demo");
                var captionText = document.getElementById("galery-caption");
                if (n > slides.length) {
                    slideIndex = 1
                }
                if (n < 1) {
                    slideIndex = slides.length
                }
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                for (i = 0; i < dots.length; i++) {
                    dots[i].className = dots[i].className.replace(" active", "");
                }
                slides[slideIndex - 1].style.display = "block";
                dots[slideIndex - 1].className += " active";
                captionText.innerHTML = dots[slideIndex - 1].alt;
            }

            // function openModal() {
            //     document.getElementById('myModal').style.display = 'block';
            // }

            // function closeModal() {
            //     document.getElementById('myModal').style.display = 'none';
            // }

            // window.onload = function () {
            //     openModal();
            // }
            // company modal

        </script>



        <script>
            const modalStart = document.getElementById("myModal");
            const closeStart = document.querySelector(".modal-img-close");


            if (!sessionStorage.getItem('popupShown')) {

                modalStart.style.display = "block";


                sessionStorage.setItem('popupShown', true);
            }

            closeStart.onclick = function() {
                modalStart.style.display = "none";
            }

        </script>


        @livewireScripts
            @stack('scripts')
            <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N7W4S4X"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
        </body>
        </html>
