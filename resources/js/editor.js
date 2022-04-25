window.theEditor;
ClassicEditor
    .create(document.querySelector('#editor'), {
        initialData: '',
        licenseKey: 'DZQGbiL2bGkCEWeQq1FcLBUE5ihAhTBP10AylXlxn1JaWnH9u2YhE+eJ',
        sidebar: {
            container: document.querySelector('#sidebar')
        },
        toolbar: {
            items: [
                'heading',
                '|',
                'bold',
                'italic',
                'link',
                '|',
                'undo',
                'redo',
                '|',
                'blockquote',
            ]
        },
        link: {
            defaultProtocol: 'https://'
        }
    })
    .then(editor => {
        // editor.plugins.get('AnnotationsUIs').switchTo('narrowSidebar');
        theEditor = editor;
        editor.editing.view.change(writer => {
            writer.setStyle(
                "height",
                "321px",
                editor.editing.view.document.getRoot()
            );
        });
    })
    .catch(error => console.error(error));
