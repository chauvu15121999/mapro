    <div class="welcome-area" id="welcome">

        <!-- ***** Header Text Start ***** -->
        <style>
           .headr h1 {
                color: white;
                font-size: 35px !important;
                margin-top: 50px;
            }
            .hear p {
                color: white;
                font-size: 25px !important;
            }
        </style>
        <div class="header-text" style="margin-top: 40px;">
            <div class="container">
                <div class="row">
                    <div class="left-text col-lg-6 col-md-6 col-sm-12 col-xs-12" data-scroll-reveal="enter left move 30px over 0.6s after 0.4s">
                        <div class="headr">
                        <h1><u>Mapro</u> giúp các nhóm làm việc có tính hợp tác hơn và làm được nhiều hơn.</h1>
                        <p>Các bảng, danh sách, và thẻ của Trello cho phép các nhóm tổ chức và ưu tiên các dự án một cách vui vẻ, linh hoạt và xứng đáng.</p>
                        <form action="register.html">
                            @csrf
                            <input name="Email" style="width: 350px; height: 60px; border-radius: 5px;" type="text" placeholder="Email">
                            <input type="submit" class="btn btn-success" value="Đăng ký - Miễn phí" style="padding: 15px; font-weight: 700; width: 180px; height: 60px; border-radius: 5px; ">
                        </form>
                    </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" data-scroll-reveal="enter right move 30px over 0.6s after 0.4s">
                        <img src="public/frontend/assets/images/slider-icon.png" class="rounded img-fluid d-block mx-auto" alt="First Vector Graphic">
                    </div>
                </div>
            </div>
        </div>
        <!-- ***** Header Text End ***** -->
    </div>