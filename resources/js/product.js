getList(); // Load product list on page load

async function getList() {
    try {
        const response = await axios.get("/get-products");
        const products = response.data;

        let productTable = $("#productTable");
        let productList = $("#productList");

        productTable.DataTable().destroy();
        productList.empty();

        if (products.length > 0) {
            products.forEach(function (product, index) {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${product.product_name}</td>
                        <td>${product.product_price}</td>
                        <td>${product.product_details}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editProduct(${
                                product.id
                            })">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${
                                product.id
                            })">Delete</button>
                        </td>
                    </tr>`;
                productList.append(row); // ✅ appending using jQuery
            });
        } else {
            productList.append(
                `<tr><td colspan="5" class="text-center">No products found</td></tr>`
            );
        }

        productTable.DataTable({
            // order: [[0, "desc"]],
            lengthMenu: [5, 10, 25, 50],
        });
    } catch (error) {
        errorToastify("Failed to fetch product list!");
    }
}

let isEditMode = false;

window.editProduct = async function (productId) {
    try {
        const response = await axios.get("/products/" + productId);
        $("#product_name").val(response.data.data.product_name);
        $("#product_price").val(response.data.data.product_price);
        $("#product_details").val(response.data.data.product_details);

        isEditMode = true; // edit mode on
        $("#product_id").val(productId);

        // $("#addProductModalLabel").html("Edit Product");
        // $("#addProductFormSubmitButton").html("Update Product");

        $("#addProductModal").modal("show");
    } catch (error) {
        errorToastify("Failed to fetch product data.!");
    }
};

// Modal close and reset the form after closing
const modalElement = document.getElementById("addProductModal");
const modal = new bootstrap.Modal(modalElement);

modalElement.addEventListener("hidden.bs.modal", function () {
    document.getElementById("addProductForm").reset();
    document.getElementById("product_id").value = "";
    isEditMode = false; // Reset to add mode
});

window.addProductFormSubmit = async function () {
    const product_name = document.getElementById("product_name").value;
    const product_price = document.getElementById("product_price").value;
    const product_details = document.getElementById("product_details").value;
    const product_id = document.getElementById("product_id").value;

    try {
        let response;

        if (isEditMode && product_id) {
            // 🔄 Update request
            response = await axios.put("/products/" + product_id, {
                product_name,
                product_price,
                product_details,
            });
        } else {
            // ➕ Create request
            response = await axios.post("/products", {
                product_name,
                product_price,
                product_details,
            });
        }

        if (response.data.status === true) {
            $("#addProductModal").modal("hide");
            document.getElementById("addProductForm").reset();
            document.getElementById("product_id").value = "";
            isEditMode = false;
            await getList();
            successToastify(response.data.message);
        }
    } catch (error) {
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors;
            Object.values(errors)
                .flat()
                .forEach((msg) => errorToastify(msg));
        } else {
            errorToastify("Something went wrong!");
        }
    }
};

window.deleteProduct = async function (productId) {
    const confirmDelete = confirm(
        "Are you sure you want to delete this product?"
    );
    if (!confirmDelete) {
        return; // ইউজার 'Cancel' করলে আর কিছু হবে না
    }

    try {
        const response = await axios.delete("/products/" + productId);

        if (response.data.success === true) {
            await getList();
            successToastify(response.data.message);
        } else {
            errorToastify(response.data.message);
        }
    } catch (error) {
        errorToastify("Something went wrong!");
    }
};
