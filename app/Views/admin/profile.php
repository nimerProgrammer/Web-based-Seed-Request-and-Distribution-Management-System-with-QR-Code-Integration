<!-- Main Content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="border-bottom">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-start">
                            <li class="breadcrumb-item"><a href="<?= base_url( '/' ) ?>">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="border-bottom">
                <h6 class="text-bold">Personal Information</h6>
            </div>
            <table class="table table-transparent  table-hover">
                <tbody>
                    <tr>
                        <td class="align-middle">
                            <?= session()->get( 'user_fullname' ) ?>
                            <br>
                            <small style="white-space: nowrap;" class="text-muted">
                                <i class="fa-solid fa-user text-secondary"></i>&nbsp;Firstname, Middlename, Lastname,
                                Suffix/Ext.
                            </small>
                        </td>
                        <td>
                            <i id="editFullnameBtn" class="bi bi-pencil-square text-primary float-end"
                                style="white-space: nowrap;" data-bs-toggle="tooltip" title="Edit"
                                data-lastname="<?= esc( $users[ 'last_name' ] ) ?>"
                                data-firstname="<?= esc( $users[ 'first_name' ] ) ?>"
                                data-middlename="<?= esc( $users[ 'middle_name' ] ) ?>"
                                data-extname="<?= esc( $users[ 'suffix_and_ext' ] ) ?>" data-bs-toggle="modal"
                                data-bs-target="#editNameModal">&nbsp;edit
                            </i>

                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">
                            <?= esc( $users[ 'gender' ] ) ?>
                            <br>
                            <?php
                            $gender = strtolower( $users[ 'gender' ] ); // "male" or "female"
                            
                            $icon = $gender === 'male'
                                ? '<i class="fa-solid fa-mars text-secondary"></i>'
                                : '<i class="fa-solid fa-venus text-secondary"></i>';
                            ?>

                            <small style="white-space: nowrap;" class="text-muted">
                                <?= $icon ?>&nbsp;Gender
                            </small>

                        </td>
                        <td>
                            <i id="editGenderBtn" class="bi bi-pencil-square text-primary float-end"
                                style="white-space: nowrap;" data-bs-toggle="tooltip" title="Edit"
                                data-gender="<?= esc( $users[ 'gender' ] ) ?>" data-bs-toggle="modal"
                                data-bs-target="#editGenderModal">&nbsp;edit
                            </i>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">
                            <?php
                            $rawBDate = $users[ 'b_date' ] ?? null;
                            $dateObj  = DateTime::createFromFormat( 'Y-m-d', $rawBDate );
                            ?>

                            <?php if ( $dateObj ) : ?>
                                <?= esc( $dateObj->format( 'F j, Y' ) ) ?>
                            <?php else : ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                            <br>
                            <small style="white-space: nowrap;" class="text-muted">
                                <i class="bi bi-calendar-event text-secondary"></i>&nbsp;Birthdate
                            </small>

                        </td>
                        <td>
                            <i id="editBirthdateBtn" class="bi bi-pencil-square text-primary float-end"
                                style="white-space: nowrap;" data-bs-toggle="tooltip" title="Edit"
                                data-bs-toggle="tooltip" title="Edit" data-birthdate="<?= esc( $users[ 'b_date' ] ) ?>"
                                data-bs-toggle="modal" data-bs-target="#editBirthdateModal">&nbsp;edit
                            </i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?= esc( $users[ 'age' ] ) ?>
                            <br>
                            <small style="white-space: nowrap;" class="text-muted">
                                <i class="fa-solid fa-hourglass-half text-secondary"></i>&nbsp;Age
                            </small>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="border-bottom">
                <h6 class="text-bold">Contact Information</h6>
            </div>
            <table class="table table-transparent  table-hover">
                <tbody>
                    <tr>
                        <td class="align-middle">
                            <?= esc( $users[ 'contact_no' ] ) ?>
                            <br>
                            <small style="white-space: nowrap;" class="text-muted">
                                <i class="fa-solid fa-phone text-secondary"></i>&nbsp;Contact Number
                            </small>

                        </td>
                        <td>
                            <i id="editContactNoBtn" class="bi bi-pencil-square text-primary float-end"
                                style="white-space: nowrap;" data-bs-toggle="tooltip" title="Edit"
                                data-contactno="<?= esc( $users[ 'contact_no' ] ) ?>" data-bs-toggle="modal"
                                data-bs-target="#editContactNoModal">&nbsp;edit
                            </i>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">
                            <?= esc( $users[ 'email' ] ) ?>
                            <br>
                            <small style="white-space: nowrap;" class="text-muted">
                                <i class="fa-solid fa-envelope text-secondary"></i>&nbsp;Email Address
                            </small>
                        </td>
                        <td>
                            <i id="editEmailBtn" class="bi bi-pencil-square text-primary float-end"
                                style="white-space: nowrap;" data-bs-toggle="tooltip" title="Edit"
                                data-email="<?= esc( $users[ 'email' ] ) ?>" data-bs-toggle="modal"
                                data-bs-target="#editEmailNoModal">&nbsp;edit
                            </i>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="border-bottom">
                <h6 class="text-bold">Account & Security</h6>
            </div>
            <table class="table table-transparent  table-hover">
                <tbody>
                    <tr>
                        <td class="align-middle">
                            <?= esc( $users[ 'username' ] ) ?>
                            <br>
                            <small style="white-space: nowrap;" class="text-muted">
                                <i class="bi bi-person-circle text-secondary"></i>&nbsp;Username
                            </small>
                        </td>
                        <td>
                            <i id="editUsernameBtn" class="bi bi-pencil-square text-primary float-end"
                                style="white-space: nowrap;" data-bs-toggle="tooltip" title="Edit"
                                data-username="<?= esc( $users[ 'username' ] ) ?>" data-bs-toggle="modal"
                                data-bs-target="#editUsernameModal">&nbsp;edit
                            </i>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="align-middle" data-bs-toggle="modal"
                            data-bs-target="#changePasswordModal" style="cursor: pointer;">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                <span>Change password</span>
                                <span class="text-primary fs-4" style="font-style: normal;">&gt;</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
<style>
    .table-transparent,
    .table-transparent th,
    .table-transparent td,
    .table-transparent thead,
    .table-transparent tbody,
    .table-transparent tr {
        background-color: transparent !important;
    }
</style>