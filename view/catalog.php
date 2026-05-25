<?php require BASE_PATH . '/view/layout/header.php'; ?>

<div class="section catalog page">
	<div class="wrapper">

		<!-- TITLE -->
		<h1>
			<?php if (!empty($search)): ?>

				Search results for "<?= htmlspecialchars($search) ?>"

				<?php if (!empty($section)): ?>
					in <?= ucfirst($section) ?>
				<?php endif; ?>

			<?php else: ?>

				<?= !empty($section) ? ucfirst($section) : 'Full Catalog' ?>

			<?php endif; ?>
		</h1>

		<!-- CHECK DATA SAFELY -->
		<?php if (isset($catalog) && count($catalog) === 0): ?>

			<?php if (!empty($search) || !empty($section)): ?>

				<p>No items were found matching your request.</p>

				<p>
					<a href="index.php?page=catalog">Browse Full Catalog</a>
				</p>

			<?php else: ?>

				<p>No catalog items available.</p>

			<?php endif; ?>

		<?php else: ?>

			<!-- PAGINATION -->
			<?php require BASE_PATH . '/view/partials/pagination.php'; ?>

			<!-- LIST -->
			<ul class="catalog">
				<?php foreach ($catalog as $item): ?>
					<?= ItemView::render($item); ?>
				<?php endforeach; ?>
			</ul>

			<!-- PAGINATION -->
			<?php require BASE_PATH . '/view/partials/pagination.php'; ?>

		<?php endif; ?>

	</div>
</div>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>