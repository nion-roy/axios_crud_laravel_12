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
