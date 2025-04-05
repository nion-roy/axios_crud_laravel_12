<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Document</title>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
	</head>

	<body>

		<div class="container">
			<h2 class="m-0 bg-success text-center rounded mt-3 text-white py-3">Crud with Ajax on Axios</h2>
		</div>

		<div class="container mt-3">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header bg-primary text-white text-center">
							<h4>Product List</h4>
						</div>
						<div class="card-body">
							<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>
						</div>

						<!-- Add Product Modal -->
						<div class="modal" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
										<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
									</div>
									<div class="modal-body">
										<form id="addProductForm">
											<div class="mb-3">
												<label for="product_name" class="form-label">Product Name</label>
												<input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter product name">
											</div>
											<div class="mb-3">
												<label for="product_price" class="form-label">Product Price</label>
												<input type="number" class="form-control" id="product_price" name="product_price" placeholder="Enter product price">
											</div>
											<div class="mb-3">
												<label for="product_details" class="form-label">Product Details</label>
												<textarea name="product_details" class="form-control" id="product_details" rows="4" cols="4" placeholder="Enter product details...."></textarea>
											</div>
											<div class="mb-3">
												<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
												<button type="button" class="btn btn-success" onclick="addProductFormSubmit()">Add Product</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

						@push('js')
							<script>
								document.addEventListener('DOMContentLoaded', function() {
									function addProductFormSubmit() {
										let product_name = document.getElementById('product_name').value;
										let product_price = document.getElementById('product_price').value;
										let product_details = document.getElementById('product_details').value;

										axios.post("{{ route('products.store') }}", {
												product_name: product_name,
												product_price: product_price,
												product_details: product_details
											})
											.then(function(response) {
												if (response.data.status == 200) {
													$('#addProductModal').modal('hide');
													document.getElementById('addProductForm').reset();
													successToastify(response.data.message);
												}
											})
											// .catch(function(error) {
											// 	errorToastify(error.response.data.errors.product_name);
											// });

											.catch(function(error) {
												if (error.response && error.response.data && error.response.data.errors) {
													let errors = error.response.data.errors;
													for (let key in errors) {
														if (errors.hasOwnProperty(key)) {
															errors[key].forEach(function(message) {
																errorToastify(message);
															});
														}
													}
												}
											});
									}

									// expose the function globally if needed
									window.addProductFormSubmit = addProductFormSubmit;
								});
							</script>
						@endpush



						<div class="card-body">
							<table class="table table-bordered table-striped" id="productTable">
								<thead>
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>Price</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody id="productList"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

		<script>
			function successToastify(message) {
				Toastify({
					text: message,
					duration: 900,
					gravity: "bottom",
					position: 'center',
					backgroundColor: "#00b09b",
				}).showToast();
			}

			function errorToastify(message) {
				Toastify({
					text: message,
					duration: 900,
					gravity: "bottom",
					position: 'center',
					backgroundColor: "#FF0000",
				}).showToast();
			}
		</script>

		@stack('js')

	</body>

</html>
