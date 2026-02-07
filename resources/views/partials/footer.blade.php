<style>
    .footer {
        background-color: #000;
        height: 13vh;
        width: 100%;
        color: white;
        text-align: center;
        padding-top: 5px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .footer p {
        margin: 3px 0;
    }

    .footer-links {
        margin-top: 5px;
    }

    .footer-links a {
        color: white;
        text-decoration: none;
        margin: 0 15px;
        font-weight: normal;
    }

    .footer-links a:hover {
        text-decoration: underline;
    }
</style>

<footer class="footer" title="Use help for user manuals.">
    <p>&copy; {{ date('Y') }} District Secretariat, Vavuniya. All Rights Reserved.</p>
    <div class="footer-links">
        <a href="{{ route('help') }}">Help</a>
        <span>|</span>
        <a href="{{ route('about') }}">About</a>
    </div>
</footer>