$(document).ready(function(){
    
    var url = "/ajax-crud/pack/tasks";
    
    //display modal form for task editing
    $('.open-modal').click(function(){
        
        var task_id = $(this).val();
        
        $.get(url + '/' + task_id, function (data) {
        
            //success data
            console.log(data);
            
            document.getElementById('pack_name').innerHTML = "PACK:: " + data.name;
            document.getElementById('pack_content').innerHTML = data.content;
            
            $('#myModal').modal('show');
            
            $('.delete-website').on('click', function() {
    
                var v = $(this).val();

                deleteWebsite(v);
    
            });
        
        })
        
    });
    
    $('.open-modal-list-website').click(function(){
        
        var url2 = "/ajax-crud/pack/list_website";
        
        var pack_id = $(this).val();
        
        var btnu = this.id;
        
        console.log(btnu);
        
        $.get(url2 + '/' + pack_id, function (data) {
            
            //success data
            console.log(data);
            
            document.getElementById('pack_name2').innerHTML = "PACK:: " + data.name;
            document.getElementById('pack_content2').innerHTML = data.content;
            
            $('#modal-list-website').modal('show');
            
            $('.delete-website').on('click', function() {
    
                var v = $(this).val();

                deleteWebsite(v);
                
                document.getElementById(btnu).click();
    
            });
            
            
            $('.add-website').on('click', function() {
                
                
            });
            
            
        
        })
        
    
    });
    
    function deleteWebsite(value) {
        
        var url = "/ajax-crud/pack/delete_website";
        
        $.get(url + '/' + value, function (data) {
            
            //success data
            console.log(data);
            
        })
        
    }
    
    
    $("#myModal").on("hidden.bs.modal", function () { location.reload(); });
    
});


