'use strict';

let quill = new Quill('#full-editor', {
  bounds: '#full-editor',
  placeholder: 'اكتب هنا...',
  modules: {
    formula: !0,
    toolbar: [
      [{ font: [] }, { size: [] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ color: [] }, { background: [] }],
      [{ script: 'super' }, { script: 'sub' }],
      [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
      [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
      [{ direction: 'rtl' }],
      ['link', 'image', 'video', 'formula'],
      ['clean']
    ]
  },
  theme: 'snow'
});

quill.on('text-change', function(delta, oldDelta, source) {
  if (source == 'api') {
  } else if (source == 'user') {
    $("#descriptionQuill").val($(".ql-editor").html())
  }
});
if (document.getElementById("full-editor_2")) {
  let quill_2 = new Quill('#full-editor_2', {
    bounds: '#full-editor_2',
    placeholder: 'اكتب هنا...',
    modules: {
      formula: !0,
      toolbar: [
        [{ font: [] }, { size: [] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ color: [] }, { background: [] }],
        [{ script: 'super' }, { script: 'sub' }],
        [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
        [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
        [{ direction: 'rtl' }],
        ['link', 'image', 'video', 'formula'],
        ['clean']
      ]
    },
    theme: 'snow'
  });

  quill_2.on('text-change', function(delta, oldDelta, source) {
    if (source == 'api') {
    } else if (source == 'user') {
      $("#descriptionQuill_2").val($("#full-editor_2 .ql-editor").html())
    }
  });
}
if (document.getElementById("full_editor_3")) {
  let quill_3 = new Quill('#full_editor_3', {
    bounds: '#full_editor_3',
    placeholder: 'اكتب هنا...',
    modules: {
      formula: !0,
      toolbar: [
        [{ font: [] }, { size: [] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ color: [] }, { background: [] }],
        [{ script: 'super' }, { script: 'sub' }],
        [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
        [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
        [{ direction: 'rtl' }],
        ['link', 'image', 'video', 'formula'],
        ['clean']
      ]
    },
    theme: 'snow'
  });

  quill_3.on('text-change', function(delta, oldDelta, source) {
    if (source == 'api') {
    } else if (source == 'user') {
      console.log($("#full_editor_3 .ql-editor").html())
      $("#quill_3").val($("#full_editor_3 .ql-editor").html())
    }
  });
}

if (document.getElementById("full_editor_4")) {
  let quill_3 = new Quill('#full_editor_4', {
    bounds: '#full_editor_4',
    placeholder: 'اكتب هنا...',
    modules: {
      formula: !0,
      toolbar: [
        [{ font: [] }, { size: [] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ color: [] }, { background: [] }],
        [{ script: 'super' }, { script: 'sub' }],
        [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
        [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
        [{ direction: 'rtl' }],
        ['link', 'image', 'video', 'formula'],
        ['clean']
      ]
    },
    theme: 'snow'
  });

  quill_3.on('text-change', function(delta, oldDelta, source) {
    if (source == 'api') {
    } else if (source == 'user') {
      $("#quill_4").val($("#full_editor_4 .ql-editor").html())
    }
  });
}
