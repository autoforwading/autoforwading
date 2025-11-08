// err show hide
$(".error").show();
setTimeout(function() { $(".error").hide(); }, 1700);

$(".success").show();
setTimeout(function() { $(".success").hide(); }, 1700);

// auto hide bootstrap alerts
window.setTimeout(function() {
  $(".alert").fadeTo(500, 0).slideUp(500, function(){
      $(this).remove(); 
  });
}, 6000);

// data table
$(document).ready(function() {
	$('#example').DataTable({
		"bPaginate": true, //this is for pagination show hide
	    "bLengthChange": true, //number of entries hide/show option
	    "bFilter": true, // search option hide show
		"order": [[ 0, "desc" ]], /*SORTING ASC || DESC */
		"pageLength": 10, /* NOMBER OR RAW IN TABLE TO SHOW */
		"lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]] /* NUMBER OF RAW I WANNA SHOW */
	});
});


// export pdf
var $table = $('#example')

$(function() {
	$('#toolbar').find('select').change(function () {
		$table.bootstrapTable('destroy').bootstrapTable({
			exportDataType: $(this).val(),
			//exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf']
			exportTypes: ['pdf','excel', 'txt'],
		})
	}).trigger('change')
})

// function queryParams(params) {
//     var options = $table.bootstrapTable('getOptions')
//     if (!options.pagination) {
//       params.limit = options.totalRows
//     }
//     return params
// }

// select box search
// (function($){
// 	let classes = ['outline-primary', 'outline-dark','outline-danger', 'info', 'secondary'];
// 	let selects = $('.search');
// 	selects.each(function(i, e){
// 		let randomClass  = classes[Math.floor(Math.random() * classes.length)];
// 		$(this).bsSelectDrop({
// 			btnClass: 'btn btn-'+classes[i],
// 			btnWidth: 'auto',
// 			darkMenu: false,
// 			showSelectionAsList: false,
// 			showActionMenu: true,
// 			showSelectedText: (count, total) => {return `${count} von ${total} Städte ausgewählt`}
// 		});
// 	})
// }(jQuery));

// select option box search
$(document).ready(function () {
	$('.search').selectize({
		sortField: 'text'
	});
});

$(document).ready(function(){
	// "toDate" enable disable in filter {index.php}
	var fromDateInput = document.getElementById("fromDate");
	var toDateInput = document.getElementById("toDate");
	fromDateInput.addEventListener("input", function() {
	    if (fromDateInput.value !== "") { toDateInput.removeAttribute("disabled"); } 
	    else { toDateInput.setAttribute("disabled", true); }
	});
});


// check uncheck do export
// JavaScript to toggle all checkboxes based on the "Select All" checkbox
$(document).ready(function(){
	document.getElementById('selectAll').addEventListener('change', function() {
	    // Get all checkboxes except the "Select All" checkbox
	    var checkboxes = document.querySelectorAll('input[type="checkbox"][name="multipledo[]"]');
	    
	    // Set all checkboxes to the state of the "Select All" checkbox
	    checkboxes.forEach(function(checkbox) {
	        checkbox.checked = document.getElementById('selectAll').checked;
	    });
	});
});

// check uncheck do export
// JavaScript to toggle all checkboxes based on the "Select All" checkbox
$(document).ready(function(){
	document.getElementById('selectIgm').addEventListener('change', function() {
	    // Get all checkboxes except the "Select All" checkbox
	    var checkboxes = document.querySelectorAll('input[type="checkbox"][name="multiplexml[]"]');
	    
	    // Set all checkboxes to the state of the "Select All" checkbox
	    checkboxes.forEach(function(checkbox) {
	        checkbox.checked = document.getElementById('selectIgm').checked;
	    });
	});
});


