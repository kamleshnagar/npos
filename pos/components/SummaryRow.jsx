import React from 'react';

const SummaryRow = ({ 
  totalQuantity = 0,
  totalSellingPrice = 0,
  finalDiscount = 0,
  returnAmount = 0,
  netAmount = 0,
  taxableAmount = 0,
  roundingOff = 0
}) => {
  const summaryData = [
    { label: 'Total Quantity', value: totalQuantity },
    { label: 'Total Selling Price', value: totalSellingPrice.toFixed(2) },
    { label: 'Final Discount', value: finalDiscount.toFixed(2) },
    { label: 'Return Amount', value: returnAmount.toFixed(2) },
    { label: 'Net Amount', value: netAmount.toFixed(2) },
    { label: 'Taxable Amount', value: taxableAmount.toFixed(2) },
    { label: 'Rounding Off', value: roundingOff.toFixed(2) }
  ];

  return (
    <div className="flex justify-center">
      <table className="table-auto w-full text-left text-sm">
        <tbody>
          {summaryData.map((item, index) => (
            <tr key={index} className="border-b">
              <td className="px-4 py-2 font-medium text-gray-700">{item.label}</td>
              <td className="px-2 py-2 font-medium text-gray-700 text-center">:</td>
              <td className="px-4 py-2 text-gray-600 text-right">{item.value}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default SummaryRow; 