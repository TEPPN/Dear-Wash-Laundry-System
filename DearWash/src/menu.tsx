import React from 'react';

export default function LaundryWebsite() {
  return (
    <div className="flex flex-col min-h-screen">
      {/* Navigation Bar */}
      <header className="w-full py-4 px-6 flex justify-between items-center">
        <div className="flex items-center">
          <img src="/api/placeholder/150/80" alt="Laundry Logo" className="h-12" />
        </div>
        <nav className="hidden md:flex space-x-8">
          <a href="#" className="text-blue-900 font-medium border border-blue-900 rounded-full px-6 py-1">HOME</a>
          <a href="#" className="text-gray-600 font-medium">SIGN</a>
          <a href="#" className="text-gray-600 font-medium">PRICE</a>
          <a href="#" className="text-gray-600 font-medium">TAKE</a>
          <a href="#" className="text-gray-600 font-medium">REVIEW</a>
        </nav>
      </header>

      {/* Hero Section */}
      <section className="w-full py-12 px-6 bg-white">
        <div className="max-w-6xl mx-auto flex flex-col md:flex-row items-center">
          <div className="md:w-1/2 mb-8 md:mb-0">
            <h1 className="text-5xl md:text-6xl font-bold text-blue-900 mb-2">LAUNDRY</h1>
            <h2 className="text-4xl md:text-5xl font-bold text-blue-500 mb-4">dry cleaning</h2>
            <p className="text-gray-600 mb-6">
              Selamat datang di Laundry Cuci Kering Kelompok Arisu<br />
              yang sangat wah, dijamin wangi muach<br />
              muach aww
            </p>
            <button className="bg-blue-500 text-white font-medium px-6 py-3 rounded-full flex items-center">
              Learn More 
              <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fillRule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clipRule="evenodd" />
              </svg>
            </button>
          </div>
          <div className="md:w-1/2">
            <img src="/api/placeholder/500/400" alt="Laundry Service Illustration" className="w-full" />
          </div>
        </div>
      </section>

      {/* Believe Your Clothes Section */}
      <section className="w-full py-16 px-6 bg-white">
        <div className="max-w-4xl mx-auto text-center mb-12">
          <h2 className="text-4xl font-bold text-blue-900 mb-4">believe your clothes</h2>
          <p className="text-blue-500 text-lg">
            pwercayakan pakaian kamu pada kami, dengan website ini, kemudahan menanti<br />
            didepan mata. kualitas terjamin
          </p>
        </div>

        <div className="max-w-5xl mx-auto flex flex-col md:flex-row justify-around items-center gap-8">
          {/* We Wash */}
          <div className="flex flex-col items-center">
            <div className="bg-gray-200 rounded-full p-8 mb-4">
              <div className="bg-blue-900 text-white p-4 rounded-lg h-16 w-16 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v16m8-8H4" />
                </svg>
              </div>
            </div>
            <h3 className="text-xl font-bold text-blue-900">We Wash</h3>
          </div>

          {/* We Dry */}
          <div className="flex flex-col items-center">
            <div className="bg-gray-200 rounded-full p-8 mb-4">
              <div className="bg-blue-900 text-white p-4 rounded-lg h-16 w-16 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>
            <h3 className="text-xl font-bold text-blue-900">We Dry</h3>
          </div>

          {/* We Deliver */}
          <div className="flex flex-col items-center">
            <div className="bg-gray-200 rounded-full p-8 mb-4">
              <div className="bg-blue-900 text-white p-4 rounded-lg h-16 w-16 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                </svg>
              </div>
            </div>
            <h3 className="text-xl font-bold text-blue-900">We Deliver</h3>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="w-full py-8 px-6 bg-blue-900 text-white mt-auto">
        <div className="max-w-4xl mx-auto text-center">
          <h3 className="text-xl mb-4">Contact Us :</h3>
          <p className="text-gray-300">
            +62 838 0095 3199 | laundrywash@gmail.com | www.laundrywash.com
          </p>
        </div>
      </footer>
    </div>
  );
}