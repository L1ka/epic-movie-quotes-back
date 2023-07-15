<div id="container"  style="display: table; height: 100vh; width: 100%;  background: linear-gradient(187.16deg, #181623 0.07%, #191725 51.65%, #0D0B14 98.75%);" >
    <div style="display: table; margin: auto; margin-top: 70px">
        <img src="{{ asset('images/movie-quotes.png') }}" alt="movie-quotes-image"  style="display:table;  width: 22px; height: 20px" />
    </div>
    <div style="display: table; margin: auto; margin-bottom: 73px; ">
        <p style="color: #DDCCAA; text-transform: uppercase;">Movie quotes</p>
    </div>
    <div class="font-size: 16px; line-height: 24px; font-weight: 400;  margin-left: 10%;">
        <p  style="margin-bottom: 24px;margin-left: 10%; color: #fff">Hola {{ $user }}!</p>
        <p  style="color: #fff; margin-left: 10%;  margin-bottom: 32px;">Thanks for joining Movie quotes! We really appreciate it. Please click the button below to verify your account:</p>
        <a id="btn" href="{{ $url }}"
        style="margin-left: 10%; color: #fff; font-size: 14px; padding: 7px 13px; margin-bottom: 40px; text-decoration: none; ; background-color: #E31221;  border-radius: 4px; text-align: center;">Verify account</a>
        <p style=" margin-left: 10%; color: #fff; margin-bottom: 24px;">If clicking doesn't work, you can try copying and pasting it to your browser:</p>
        <a style="margin-left: 10%; color: #DDCCAA; margin-bottom: 40px; display: table;  word-wrap: break-word;" href="">{{ $url }}</a>
        <p style="margin-left: 10%; color: #fff; margin-bottom: 24px; ">If you have any problems, please contact us: <a href="mailto:support@moviequotes.ge" style="text-decoration:none; outline: none; color: #fff;">support@moviequotes.ge</a></p>
        <p style="margin-left: 10%; color: #fff">MovieQuotes Crew</p>
    </div>
</div>

