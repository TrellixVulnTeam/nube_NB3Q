/**
 * @file
 * Contains all javascript logic for consum_iam.
 */

(function ($, Drupal, drupalSettings) {
  $(document).ready(function () {
    let client_id = drupalSettings.consum_openid.client_id;
    let session_state = drupalSettings.consum_openid.session_state;
    let mes = client_id + " " + session_state;
    let targetOrigin = drupalSettings.consum_openid.target_origin;
    let loggedIn = drupalSettings.consum_openid.logged_in;
    let authorizationEndpoint = drupalSettings.consum_openid.authorization_endpoint;

    check_session();

    function check_session() {
      if (client_id !== null && client_id.length !== 0 && client_id !== 'null' && session_state !== null &&
        session_state.length !== 0 && session_state !== 'null') {
        let win = document.getElementById("opIFrame").contentWindow;
        win.postMessage(mes, targetOrigin);
      }
    }

    window.addEventListener("message", receiveMessage, false);

    function receiveMessage(e) {

      if (targetOrigin.indexOf(e.origin) < 0) {
        return;
      }

      if (e.data === "changed") {
        if (loggedIn) {
          console.log("[RP] session state has changed. sending passive request");
          if (authorizationEndpoint !== null
            && authorizationEndpoint.length !== 0
            && authorizationEndpoint !== 'null') {

            var clientId = client_id;
            var scope = 'openid internal_application_mgt_view';
            var responseType = 'code';
            var redirectUri = window.location.href;
            var prompt = 'none';
            window.top.location.href = authorizationEndpoint + '?client_id=' + clientId + "&scope=" + scope +
              "&response_type=" + responseType + "&redirect_uri=" + redirectUri + "&prompt=" + prompt;
          }
        }
      }
      else if (e.data === "unchanged") {
        console.log("[RP] session state has not changed");
      }
      else {
        console.log("[RP] error while checking session status");
      }
    }
  });
})(jQuery, Drupal, drupalSettings);


