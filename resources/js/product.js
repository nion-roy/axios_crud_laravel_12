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
            order: [[0, "desc"]],
            lengthMenu: [5, 10, 25, 50],
        });
    } catch (error) {
        errorToastify("Failed to fetch product list!");
    }
}

window.addProductFormSubmit = async function () {
    const product_name = document.getElementById("product_name").value;
    const product_price = document.getElementById("product_price").value;
    const product_details = document.getElementById("product_details").value;

    try {
        const response = await axios.post("/products", {
            product_name,
            product_price,
            product_details,
        });

        if (response.data.status === true) {
            $("#addProductModal").modal("hide");
            document.getElementById("addProductForm").reset();
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
