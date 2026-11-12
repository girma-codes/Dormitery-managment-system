/* ==============================================
   UoG Dormitory Management System - main.js
   ============================================== */

$(document).ready(function () {

    /* ---- Auto-dismiss alerts ---- */
    setTimeout(function () {
        $('.alert-dismissible').fadeOut(600, function(){ $(this).remove(); });
    }, 5000);

    /* ---- Password toggle ---- */
    $(document).on('click', '.toggle-password', function () {
        var $input = $($(this).data('target'));
        var isPass = $input.attr('type') === 'password';
        $input.attr('type', isPass ? 'text' : 'password');
        $(this).find('i')
               .toggleClass('fa-eye',    !isPass)
               .toggleClass('fa-eye-slash', isPass);
    });

    /* ---- Confirm delete ---- */
    $(document).on('click', '.btn-delete', function (e) {
        if (!confirm('Are you sure you want to delete this record? This action cannot be undone.')) {
            e.preventDefault();
            return false;
        }
    });

    /* ---- Live table search ---- */
    $('#tableSearch').on('keyup', function () {
        var value = $(this).val().toLowerCase();
        $('#searchableTable tbody tr').each(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    /* ---- Mark active sidebar link ---- */
    var current = window.location.pathname.split('/').pop();
    $('.sidebar-menu li a').each(function () {
        var href = $(this).attr('href');
        if (href && href.split('/').pop() === current) {
            $(this).addClass('active');
        }
    });

    /* ---- Responsive sidebar toggle ---- */
    $('#sidebarToggle').on('click', function () {
        $('.sidebar').toggleClass('open');
    });

    /* ---- Print ---- */
    $('#printReport, #printBtn').on('click', function () {
        window.print();
    });

    /* ---- Tooltips & Popovers ---- */
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    /* ---- Counter animation for stat numbers ---- */
    $('.stat-num, .w-num').each(function () {
        var $el    = $(this);
        var target = parseInt($el.text().replace(/\D/g,''), 10);
        if (!isNaN(target) && target > 0) {
            $({ count: 0 }).animate({ count: target }, {
                duration : 1200,
                easing   : 'swing',
                step     : function () {
                    $el.text(Math.ceil(this.count) + ($el.text().indexOf('+') > -1 ? '+' : ''));
                },
                complete : function () {
                    $el.text(target + ($el.text().indexOf('+') > -1 ? '+' : ''));
                }
            });
        }
    });

    /* ---- Select2 for block/room selects if available ---- */
    if ($.fn.select2) {
        $('select.form-control').select2({ width: '100%' });
    }

});
