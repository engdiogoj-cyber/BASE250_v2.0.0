import '../styles/globals.css';
import Head from 'next/head';

function MyApp({ Component, pageProps }) {
  return (
    <>
      <Head>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="theme-color" content="#2563eb" />
        <meta name="description" content="BASE250 - Sistema de Gestão de Imóveis" />
        <link rel="manifest" href="/manifest.json" />
        <link rel="apple-touch-icon" href="/icon-192x192.png" />
        <title>BASE250 - Gestão de Imóveis</title>
      </Head>
      <Component {...pageProps} />
    </>
  );
}

export default MyApp;
