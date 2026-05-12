import { useEffect, useState } from 'react'
import api from '../api/axios'

export default function Brands() {

    const [brands, setBrands] = useState([])
    const [loading, setLoading] = useState(true)

    async function getBrands() {

        try {

            const response = await api.get('/brands')

            setBrands(response.data)

        } catch (error) {

            console.log(error)

        } finally {

            setLoading(false)

        }
    }

    useEffect(() => {

        getBrands()

    }, [])

    return (

        <div className='min-h-screen bg-gray-100 p-6'>

            <div className='max-w-5xl mx-auto'>

                <div className='flex items-center justify-between mb-6'>

                    <h1 className='text-3xl font-bold'>
                        Marcas
                    </h1>

                    <button className='bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg'>
                        Nova Marca
                    </button>

                </div>

                {
                    loading
                        ? (
                            <p>Carregando...</p>
                        )
                        : (
                            <div className='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'>

                                {
                                    brands.map((brand) => (

                                        <div
                                            key={brand.uuid}
                                            className='bg-white rounded-xl shadow p-5'
                                        >

                                            <h2 className='text-xl font-semibold mb-2'>
                                                {brand.name}
                                            </h2>

                                            <p className='text-gray-500'>
                                                UUID: {brand.uuid}
                                            </p>

                                        </div>
                                    ))
                                }

                            </div>
                        )
                }

            </div>

        </div>

    )
}