<?= loadPartials('head') ?>
<?= loadPartials('navbar') ?>


<section>
    <div class="container mx-auto p-4 mt-4">
        <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3"> <?=$status ?> </div>
        <p class="text-center text-2xl mb-4">
            <?=$message ?>
        </p>
        <div class="text-center">
            <a class="text-center items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="/listings">Go Back to listings</a>
        </div>
    </div>
</section>


<?= loadPartials('footer') ?>