
$(document).ready(function() {
    $('form').on('submit', function(e) {
        $('input, textarea').each(function() {
            var field = $(this);
            var id = field.attr('id');
            var countspan = $("#charCount" + id);
            if(countspan.length > 0) { // if this field has a character count span
                var textLength = field.val().length;
                var min = parseInt(countspan.attr('data-min'));
                var max = parseInt(countspan.attr('data-max'));
                if(textLength < min || textLength > max) {
                    error_noti('Field ' + id + ' is not within the valid character count range!');
                    e.preventDefault();
                    return false; // this will stop the loop
                }
            }
        });
    });
});
    tinymce.init({
        toolbar: "undo redo |image formatselect link | blocks | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | code | wordcount | lists | advlist",
        block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6',
        plugins: 'code wordcount lists advlist',
      selector: '.my-textarea',
      promotion: false,
      height: 800,
    menubar: true,
    menubar: 'custom',
  menu: {
    custom: { title: 'Custom menu', items: 'basicitem nesteditem toggleitem' }
  },
  setup: function (editor) {
    var toggleState = false;
    editor.ui.registry.addMenuItem('basicitem', {
      text: 'My basic menu item',
      onAction: function () {
        editor.insertContent('<p>Here\'s some content inserted from a basic menu!</p>');
      }
    });
    editor.ui.registry.addNestedMenuItem('nesteditem', {
      text: 'My nested menu item',
      getSubmenuItems: function () {
        return [
          {
            type: 'menuitem',
            text: 'My submenu item',
            onAction: function () {
              editor.insertContent('<p>Here\'s some content inserted from a submenu item!</p>');
            }
          }
        ];
      }
    });
    editor.ui.registry.addToggleMenuItem('toggleitem', {
      text: 'My toggle menu item',
      onAction: function () {
        toggleState = !toggleState;
        editor.insertContent('<p class="toggle-item">Here\'s some content inserted from a toggle menu!</p>');
      },
      onSetup: function (api) {
        api.setActive(toggleState);
        return function () {};
      }
    });
  },
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
  plugins: [
    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
    'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
    'table'
  ],
  toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
    'forecolor backcolor emoticons | help',
  menu: {
    favs: { title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons', },
    custom: { title: 'Custom menu', items: 'basicitem nesteditem toggleitem' ,},
  },
  menubar: 'favs file edit view insert format tools table help custom',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
    });
    function updateTinyMCE(triggeringTextArea, triggeredById) {
      var dynamicContent = triggeringTextArea;
  
      // Get the TinyMCE editor instance
      var editor = tinymce.get(triggeredById);
  
      if (editor) {
          // Set the content of TinyMCE
          editor.setContent(dynamicContent);
      }
  }