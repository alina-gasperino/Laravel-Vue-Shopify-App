function bindEvent(element, eventName, eventHandler) {
    if (element.addEventListener) {
        element.addEventListener(eventName, eventHandler, false);
    } else if (element.attachEvent) {
        element.attachEvent('on' + eventName, eventHandler);
    }
}

bindEvent(window, 'message', function (e) {
    if (e && e.data && typeof e.data === 'string') {
        try {
            var data = JSON.parse(e.data);
            if (data && data.type === 'DSHD_GOTO_PROJECT') {
                var project_id = data.payload.project_id;//open editor with this project_id}} catch {}}});EditorThe editor iframe can be loaded on its own page or via a popup/dynamic page takeover. Using the Design Huddle JavascriptSDK to create and facilitate communication with the iframe is recommended.
                console.log(project_id);
                //open editor with this project_id
            }
        } catch {
        }
    }
})
