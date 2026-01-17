$(document).ready(function () {
    updateCartCount();

    // --- USER SIDE ---

    // Load Products (Index Page)
    if ($('#products-grid').length) {
        $.ajax({
            url: 'php/products.php',
            type: 'GET',
            dataType: 'json',
            success: function (products) {
                let html = '';
                if (products.length === 0) {
                    html = '<p>No products found.</p>';
                } else {
                    products.forEach(p => {
                        html += `
                            <div class="product-card">
                                <img src="uploads/${p.image}" alt="${p.name}" onerror="this.src='https://via.placeholder.com/150'">
                                <h3>${p.name}</h3>
                                <p class="price">${p.price} DA</p>
                                <button onclick="addToCart(${p.id})">Add to Cart</button>
                            </div>
                        `;
                    });
                }
                $('#products-grid').html(html);
            },
            error: function () {
                $('#products-grid').html('<p style="color:red">Error loading products.</p>');
            }
        });
    }

    // Load Cart (Cart Page)
    if ($('#cart-content').length) {
        loadCart();
    }

    // --- ADMIN SIDE ---

    // Load Admin Products
    if ($('#productsTable').length) {
        loadAdminProducts();

        // Add Product
        $('#addProductForm').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: 'php/add_product.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        alert('Product added!');
                        $('#addProductForm')[0].reset();
                        loadAdminProducts();
                    } else {
                        alert('Error: ' + res.message);
                    }
                }
            });
        });

        // Edit Product Form
        $('#editProductForm').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: 'php/update_product.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        alert('Product updated!');
                        $('#editModal').hide();
                        loadAdminProducts();
                    } else {
                        alert('Error: ' + res.message);
                    }
                }
            });
        });

        // Close Modal
        $('.close').click(function () {
            $('#editModal').hide();
        });
    }
});

// --- FUNCTIONS ---

function addToCart(id) {
    $.post('php/cart.php', { action: 'add', id: id }, function (res) {
        if (res.success) {
            updateCartCount();
        }
    }, 'json');
}

function updateCartCount() {
    $.getJSON('php/cart.php?action=fetch', function (res) {
        let count = 0;
        if (res.items) {
            res.items.forEach(item => count += item.qty);
        }
        $('#cart-count').text(count);
    });
}

function loadCart() {
    $.getJSON('php/cart.php?action=fetch', function (res) {
        if (!res.items || res.items.length === 0) {
            $('#cart-content').html('<p>Your cart is empty.</p>');
            $('#cart-summary').html('');
            return;
        }

        let html = `<table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead><tbody>`;

        res.items.forEach(p => {
            html += `
                <tr>
                    <td>
                        <img src="uploads/${p.image}" style="width:50px;height:50px;object-fit:cover;">
                        ${p.name}
                    </td>
                    <td>${p.price} DA</td>
                    <td>
                        <input type="number" value="${p.qty}" min="1" onchange="updateQty(${p.id}, this.value)">
                    </td>
                    <td>${p.subtotal} DA</td>
                    <td><button class="btn-delete" onclick="removeFromCart(${p.id})">Remove</button></td>
                </tr>
            `;
        });

        html += `</tbody></table>`;
        $('#cart-content').html(html);
        $('#cart-summary').html(`<h3>Total: ${res.total} DA</h3>`);
    });
}

function updateQty(id, qty) {
    $.post('php/cart.php', { action: 'update', id: id, qty: qty }, function (res) {
        if (res.success) {
            loadCart();
            updateCartCount();
        }
    }, 'json');
}

function removeFromCart(id) {
    if (!confirm('Remove this item?')) return;
    $.post('php/cart.php', { action: 'remove', id: id }, function (res) {
        if (res.success) {
            loadCart();
            updateCartCount();
        }
    }, 'json');
}

function loadAdminProducts() {
    $.getJSON('php/products.php', function (products) {
        let html = '';
        products.forEach(p => {
            html += `
                <tr>
                    <td><img src="uploads/${p.image}" style="width:50px;"></td>
                    <td>${p.name}</td>
                    <td>${p.price} DA</td>
                    <td>
                        <button onclick="openEditModal(${p.id}, '${p.name}', ${p.price})">Edit</button>
                        <button class="btn-delete" onclick="deleteProduct(${p.id})">Delete</button>
                    </td>
                </tr>
            `;
        });
        $('#productsTable tbody').html(html);
    });
}

function deleteProduct(id) {
    if (!confirm('Are you sure?')) return;
    $.post('php/delete_product.php', { id: id }, function (res) {
        if (res.success) {
            loadAdminProducts();
        } else {
            alert('Error deleting product');
        }
    }, 'json');
}

function openEditModal(id, name, price) {
    $('#edit-id').val(id);
    $('#edit-nom').val(name); // Input ID remains 'edit-nom' in HTML, but value is 'name'
    $('#edit-prix').val(price); // Input ID remains 'edit-prix' in HTML
    $('#editModal').show();
}
