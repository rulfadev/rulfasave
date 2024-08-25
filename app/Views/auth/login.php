<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>
  <div class="bg-white rounded-lg shadow-lg flex max-w-4xl w-full overflow-hidden">
    <!-- Bagian Formulir -->
    <div class="w-full md:w-1/2 p-8 m-auto">
        <div class="text-center mb-10">
          <a class="toggleColour text-black no-underline hover:no-underline font-bold text-2xl lg:text-4xl uppercase" href="<?= base_url(); ?>">
            <svg class="h-9 fill-current inline" viewBox="0 0 32 38" xmlns="http://www.w3.org/2000/svg" stroke="#444"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g data-name="16. Money Bag" id="_16._Money_Bag"> <path d="M28.84,24.18A23.4,23.4,0,0,0,21.4,11.64a3,3,0,0,0-1-5.6L22,1.32a1,1,0,0,0-.14-.9A1,1,0,0,0,21,0H11a1,1,0,0,0-.81.42,1,1,0,0,0-.14.9L11.63,6a3,3,0,0,0-1,5.6A23.4,23.4,0,0,0,3.15,24.2,6.57,6.57,0,0,0,3,25.59,6.41,6.41,0,0,0,9.41,32H22.59A6.41,6.41,0,0,0,29,25.59,6.29,6.29,0,0,0,28.84,24.18ZM19.61,2,18.28,6H13.72L12.39,2ZM12,8h8a1,1,0,0,1,0,2H12a1,1,0,0,1,0-2ZM22.59,30H9.41A4.42,4.42,0,0,1,5,25.59a4.82,4.82,0,0,1,.11-1A21.45,21.45,0,0,1,13.32,12h5.36A21.49,21.49,0,0,1,26.9,24.64a5,5,0,0,1,.1.95A4.42,4.42,0,0,1,22.59,30Z"></path> <path d="M16,18h2a1,1,0,0,0,0-2H17a1,1,0,0,0-2,0v.18A3,3,0,0,0,16,22a1,1,0,0,1,0,2H14a1,1,0,0,0,0,2h1a1,1,0,0,0,2,0v-.18A3,3,0,0,0,16,20a1,1,0,0,1,0-2Z"></path> </g> </g></svg>
            <?= $judul ?>
          </a>
        </div>
        <?php if (session()->getFlashdata('error')) : ?>
          <p style="color: red;"><?= session()->getFlashdata('error') ?></p>
        <?php elseif (session()->getFlashdata('success')) : ?>
          <p style="color: green;"><?= session()->getFlashdata('success') ?></p>
        <?php endif; ?>
        <form action="<?= base_url('auth/login'); ?>" method="post">
            <div class="mb-4">
                <label for="login" class="block text-gray-700">Nama Pengguna atau Email</label>
                <input type="text" id="login" name="login" class="w-full p-3 rounded border border-gray-300 focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700">Kata Sandi</label>
                <input type="password" id="password" name="password" class="w-full p-3 rounded border border-gray-300 focus:outline-none focus:border-blue-500" required>
            </div>
            <div class="flex items-center mb-6">
                <input type="checkbox" id="remember_me" name="remember_me" value="1" class="h-4 w-4 text-blue-500 focus:ring-blue-400 border-gray-300 rounded mr-2">
                <label for="remember_me" class="ml-2 text-gray-700 text-sm">Remember Me</label>
            </div>
            <button type="submit" class="w-full gradient text-white p-3 rounded font-bold hover:gradientback transition-colors duration-300">
                <svg class="w-6 h-6 inline-block" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#fff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M8 16C8 18.8284 8 20.2426 8.87868 21.1213C9.51998 21.7626 10.4466 21.9359 12 21.9827M8 8C8 5.17157 8 3.75736 8.87868 2.87868C9.75736 2 11.1716 2 14 2H15C17.8284 2 19.2426 2 20.1213 2.87868C21 3.75736 21 5.17157 21 8V10V14V16C21 18.8284 21 20.2426 20.1213 21.1213C19.3529 21.8897 18.175 21.9862 16 21.9983" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path> <path d="M3 9.5V14.5C3 16.857 3 18.0355 3.73223 18.7678C4.46447 19.5 5.64298 19.5 8 19.5M3.73223 5.23223C4.46447 4.5 5.64298 4.5 8 4.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path> <path d="M6 12L15 12M15 12L12.5 14.5M15 12L12.5 9.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                Masuk
            </button>
            <p class="mt-6 text-xs text-gray-600 text-center">
                Apakah anda belum memiliki akun?
                <a href="<?= base_url('auth/register'); ?>" class="border-b border-gray-500 border-dotted font-bold">
                    Daftar Sekarang
                </a>
            </p>
        </form>
    </div>
    <!-- Bagian Gambar -->
    <div class="hidden md:block md:w-1/2 lg:flex bg-blue-100 custom-image-height">
        <img src="https://storage.googleapis.com/devitary-image-host.appspot.com/15848031292911696601-undraw_designer_life_w96d.svg" alt="Gambar Ilustrasi" class="hidden md:block w-full h-full object-fit p-5">
    </div>
  </div>
<?= $this->endSection() ?>