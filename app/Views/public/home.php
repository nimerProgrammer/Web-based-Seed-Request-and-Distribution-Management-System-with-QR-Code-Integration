<!-- Main Content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="border-bottom">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-start">
                            <li class="breadcrumb-item top-loader"><a href="<?= base_url( '/' ) ?>">Home</a></li>
                            <!-- <li class="breadcrumb-item active">Reports</li> -->
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">

                <?php foreach ( $posts as $index => $post ) : ?>
                    <div class="card-body">
                        <!-- <div class="card-header"> -->
                        <!-- Date at the top -->
                        <div class="mb-1">
                            Posted:
                            <?php
                            $rawCreated = $post[ 'created_at' ];
                            $createdObj = DateTime::createFromFormat( 'm-d-Y h:i:s A', $rawCreated );
                            if ( $createdObj ) :
                                ?>
                                <?= $createdObj->format( 'F j, Y' ) ?>
                                <small class="text-muted"><?= $createdObj->format( 'h:i:s A' ) ?></small>
                            <?php endif; ?>
                        </div>
                        <!-- </div> -->

                        <!-- Image Carousel -->
                        <!-- <div class="card-body"> -->
                        <div id="carouselPost<?= $index ?>" class="carousel slide" data-bs-ride="carousel">
                            <?php if ( count( $post[ 'images' ] ) > 1 ) : ?>
                                <div class="carousel-indicators">
                                    <?php foreach ( $post[ 'images' ] as $imgIndex => $img ) : ?>
                                        <button type="button" data-bs-target="#carouselPost<?= $index ?>"
                                            data-bs-slide-to="<?= $imgIndex ?>" class="<?= $imgIndex === 0 ? 'active' : '' ?>"
                                            aria-current="<?= $imgIndex === 0 ? 'true' : 'false' ?>"
                                            aria-label="Slide <?= $imgIndex + 1 ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="carousel-inner">
                                <?php foreach ( $post[ 'images' ] as $imgIndex => $img ) : ?>
                                    <div class="carousel-item <?= $imgIndex === 0 ? 'active' : '' ?>">
                                        <img src="<?= base_url( $img[ 'image_path' ] ) ?>" class="d-block w-100 h-100"
                                            style="object-fit: cover;" alt="Post Image">
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php if ( count( $post[ 'images' ] ) > 1 ) : ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselPost<?= $index ?>"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselPost<?= $index ?>"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            <?php endif; ?>
                        </div>

                        <!-- Description -->

                        <p class="mt-1">
                            <?= esc( $post[ 'description' ] ) ?>.
                        </p>
                    </div>
                    <div class="border-bottom"></div>

                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>