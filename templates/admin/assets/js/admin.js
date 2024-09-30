$(document).ready(function () {

    var url = $('table').attr('url');
    
      
//   configuracoes padroes para todas tabelas
    $.extend($.fn.dataTable.defaults, {
        language: {
            url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/pt-PT.json'
        },
        
        layout: {
        topStart: {
            buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5','printHtml5']
        }
    }
    });
   
   
    $('#tabelaCategorias').DataTable({
        columnDefs: [
        {
            targets: [3,4],
            orderable: false
        }
    ]
    });
    
    $('#tabelaPost').DataTable({
        language: {
            paginate: {
                "first": "«",
                "last": "»",
                "next": "›",
                "previous": "‹"
            },
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registos",
        },
        processing: true,
        serverSide: true,
        ajax: url + 'admin/posts/datatable',
        columns: [
            null,
            {
                data: null,
                render: function (data, type, row) {
                    if (row[1]) {

                       return '<a href="' + url + 'uploads/images/' + row[1] + '"  data-lightbox="image-1"><img width="40px" height="30px" src=" ' + url + 'uploads/images/thumbs/thumb-' + row[1] + '"></a>';

                    } else {
                        return '<i class="bi bi-image fs-3 text-secundary"></i>';
                    }
                }
            },
            null, null, null,
            {
                data: null,
                render: function (data, type, row) {
                    if (row[5] === 1) {

                        return '<div class="d-flex justify-content-center "><i class="bi bi-record-fill text-success text-center"></i></div>';

                    } else {
                        return '<div class="d-flex justify-content-center"><i class="bi bi-record-fill text-danger text-center"></i></div>';
                    }
                }
            },
            null, null
        ]

    });
});


$(document).ready(function () {
    $('#summernote').summernote({
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['style']],
            ['fontname', ['fontname']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['table', ['table']],
            ['view', ['help']]
        ],
        fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Roboto', 'Open Sans', 'Lobster'],
        fontNamesIgnoreCheck: ['Roboto', 'Open Sans', 'Lobster'],
        table: [
            ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
            ['delete', ['deleteRow', 'deleteCol', 'deleteTable']]
        ],
        styleTags: [
            'p',
            {title: 'Blockquote', tag: 'blockquote', className: 'blockquote', value: 'blockquote'},
            'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'
        ],
        fontSizeUnits: ['px', 'pt'],
        lineHeights: ['0.2', '0.3', '0.4', '0.5', '0.6', '0.8', '1.0', '1.2', '1.4', '1.5', '2.0', '3.0'],
        placeholder: 'Escreva seu texto aqui...'
    });
    $('#summernote').summernote('fontSizeUnit', 'pt');
    $('#summernote').summernote('fontSize', 12);
});

/**
 * Initiate Datatables
 */

//    document.addEventListener('DOMContentLoaded', function () {
//        $('.datatable').DataTable({
//             responsive: true,
//            paging: true,  // Ativa a paginação
//            searching: true,  // Ativa a pesquisa
//            ordering: true,  // Ativa a ordenação
//            lengthMenu: [5, 10, 25, 50, 100],
//            language: {
//                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-PT.json'
//            }
//        });
//    });
//


   