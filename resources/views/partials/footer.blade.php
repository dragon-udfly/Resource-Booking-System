<style>
    .footer {
        background-color: #000;
        height: 13vh;
        width: 100%;
        color: white;
        text-align: center;
        padding-top: 20px;
    }
</style>

<footer class="footer">
    <p>
        &copy; {{ date('Y') }} District Secretariat, Vavuniya. All Rights Reserved.
        <br />
        <a href="{{ route('help') }}" style="color: white; text-decoration: none;">Help</a> |
        <a href="{{ route('about') }}" style="color: white; text-decoration: none;">About</a>
    </p>
    <br />
</footer>