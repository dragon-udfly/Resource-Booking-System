<div id="requester-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3); max-width: 400px; text-align: center;">
        <p id="requester-message" style="font-size: 1.2em; color: #333; margin-bottom: 20px;"></p>
        <div id="requester-button-group" style="display: flex; justify-content: center; gap: 10px;">
            <button id="requester-confirm-btn" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em;">OK</button>
            <button id="requester-cancel-btn" style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 1em; display: none;">Cancel</button>
        </div>
    </div>
</div>

<style>
    #requester-overlay {
        display: flex; /* Ensures content is centered */
    }
</style>
