function GetPrize()
{
    $.ajax({
        type: 'POST',
        url: 'prize/get-prize',
        dataType: 'json',
        async: true,
        success: function(data) {
            GenerateWinPrizeWindow(data);
        }
    });
}

function GenerateWinPrizeWindow(data)
{
   let buttons = {};

   if(data.convert_text)
   {
       buttons = {
           cancel: data.decline_text,
           accept: {
               text: data.accept_text,
               value: "accept",
           },
           convert: {
               text: data.convert_text,
               value: "convert",
           }
       };
   }
   else
   {
       buttons = {
           cancel: data.decline_text,
           accept: {
               text: data.accept_text,
               value: "accept",
           },
        };
   }

    swal("You win " + data.count+" "+data.name +" What do you want to do?", {
        buttons: buttons,
    })
    .then((value) => {
        switch (value) {

            case "accept":
                AcceptPrize(data.id);
                break;

            case "convert":
                AcceptPrize(data.id, 1);
                break;

            default:
                DeclinePrize(data.id);
                break;
        }
    });
}

function AcceptPrize(id, is_convert=0)
{
    $.ajax({
        type: 'POST',
        url: 'prize/accept-prize',
        data: {'id': id, 'is_convert': is_convert},
        dataType: 'json',
        async: true,
        success: function(data) {
            if(data.result === true)
                swal({
                    title: "Accepted",
                    text: "Your prize accepted successful",
                    icon: "success",
                    buttons: {
                        'OK': 'OK',
                    }
                })
                .then((willDelete) => {
                    location.reload();
                });
            else
                swal("Server error", "Something went wrong", "error");
        }
    });
}

function DeclinePrize(id)
{
    $.ajax({
        type: 'POST',
        url: 'prize/decline-prize',
        data: {'id': id},
        dataType: 'json',
        async: true,
        success: function(data) {
            if(data.result === true)
                swal("Declined", "Your prize declined successful", "info");
            else
                swal("Server error", "Something went wrong", "error");
        }
    });
}

