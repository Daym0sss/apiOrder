function addProduct(event)
{
    event.preventDefault();

    var products = document.body.getElementsByClassName('product');
    document.getElementById('createBtn').remove();
    var number = products.length + 1;
    var newDiv = '';

    newDiv += '<div class="product"> <br><br> <label style="padding-right: 30px">Product Information</label>';
    newDiv += '<input type="text" placeholder="Product number" name="productNumber' + number + '"> ';
    newDiv += '<input type="text" placeholder="Product name" name="productName' + number + '"> ';
    newDiv += '<input type="text" placeholder="Product nds" name="productNds' + number + '"> ';
    newDiv += '<input type="text" placeholder="Product price" name="productPrice' + number + '"> ';
    newDiv += '<input type="text" placeholder="Product quantity" name="productQuantity' + number + '"><br><br>';
    newDiv += '<label style="padding-right: 30px">Box Information</label>';
    newDiv += '<input type="text" placeholder="Box barcode" name="boxBarcode' + number + '"> ';
    newDiv += '<input type="text" placeholder="Box weight" name="boxWeight' + number + '"> ';
    newDiv += '<input type="text" placeholder="Box sixeX" name="boxSizeX' + number + '"> ';
    newDiv += '<input type="text" placeholder="Box sixeY" name="boxSizeY' + number + '"> ';
    newDiv += '<input type="text" placeholder="Box sixeZ" name="boxSizeZ' + number + '"> ';
    newDiv += '<br><br> </div>';
    newDiv += '<input type="submit" id = \'createBtn\' value="Create">';
    document.getElementById('orderForm').innerHTML += newDiv;

}
function deleteProduct(event)
{
    event.preventDefault();

    var products = document.body.getElementsByClassName('product');
    if (products.length > 1)
    {
        products[products.length - 1].remove();
    }
}