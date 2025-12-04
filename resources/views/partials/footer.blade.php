<style>
    /* Footer-Specific Styles */
    .common-footer {
        background-color: #000;
        width: 100%;
        color: white;
        text-align: center;
        padding-top: 20px;
        /* Ensure it stays at the bottom if content is short */
        margin-top: auto; 
    }
    
    .common-footer p {
        margin: 5px 0;
    }

    .common-footer a {
        color: white; 
        text-decoration: none; 
        margin: 0 10px;
    }
    
    .common-footer a:hover {
        text-decoration: underline;
    }
</style>

<footer class="common-footer">
    <p>&copy; {{ date('Y') }} District Secretariat, Vavuniya. All Rights Reserved.</p>
    <br />
</footer>