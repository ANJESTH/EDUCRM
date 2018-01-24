$ = jQuery.noConflict();

$(document).ready(function(){

    forceFixHeader();

    $('.navmenu ul li a').click(function() {
        $('html, body').animate({scrollTop: $(this.hash).offset().top - 80}, 800);
        return false;
    });

});

function forceFixHeader() {
    // Fixed Header
    var $eduscrumHeader = $("#menuF");
    $eduscrumHeader.data('position', $eduscrumHeader.position() );

    $(window).scroll( function( event ) {

        event.preventDefault();

        var currentScroll 	= getScroll(),
			scrollTop 		= currentScroll.top;

        if ( scrollTop > 200 ) {

            $eduscrumHeader.fadeOut('fast',function() {
                $(this).removeClass("default")
                .addClass("fixed transbg")
                .fadeIn('slow');
            });
        }
        else if(  scrollTop < 100 ) {

            $eduscrumHeader.fadeOut('fast',function() {
                $(this).removeClass("fixed transbg")
                .addClass("default")
                .fadeIn('fast');
            });

        }

    });
}

function getScroll() {
    var b = document.body;
    var e = document.documentElement;
    return {
        left: parseFloat( window.pageXOffset || b.scrollLeft || e.scrollLeft ),
        top: parseFloat( window.pageYOffset || b.scrollTop || e.scrollTop )
    };
}

function confirmDeleteInstitute() {
	return confirm( 'Are you sure you want to delete this item? This will delete courses and applications under this institution too.' );
}

function confirmDeleteCourse() {
	return confirm( 'Are you sure you want to delete this item? This will delete applications under this course too.' );
}
