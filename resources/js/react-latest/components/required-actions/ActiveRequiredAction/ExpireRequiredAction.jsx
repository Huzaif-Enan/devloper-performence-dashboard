import React from "react";
import RequiredActionsCard from "../RequiredActionCards/RequiredActionsCard";
import FilterBar from "../FilterBar/FilterBar";
import _ from "lodash";
import { usePagination } from "../Pagination";
import { useState } from "react";
import { useEffect } from "react";
import { useRefresh } from "../Index";
import { useLazyGetExpiredRequiredActionQuery } from "../../../services/api/requiredActionApiSlice";
import RequiredActionCard_Loader from "../RequiredActionCards/RequiredActionCard_Loader";

const ExpireRequiredAction = () => {
    const { currentPage, perPageItem, setTotalItem } = usePagination();
    const { refresh } = useRefresh();
    const [data, setData] = useState([]);
    const [filterData, setFilterData] = useState([]);
    const [slicedData, setSlicedData] = useState([]);
    const [dateFilter, setDateFilter] = useState({});
    const [searchFilter, setSearchFilter] = useState("");
    const [viewFilter, setViewFilter] = useState("");
    const [getExpiredRequiredAction, { isLoading, isFetching }] =
        useLazyGetExpiredRequiredActionQuery();

    // data fetching according to filter
    useEffect(() => {
        const queryObj = _.pickBy(dateFilter, Boolean);
        const query = new URLSearchParams(queryObj).toString();
        getExpiredRequiredAction(query)
            .unwrap()
            .then(({ pending_actions }) => {
                setData(pending_actions);
            });
    }, [dateFilter, refresh]);

    // filter data according to search
    useEffect(() => {
        if (searchFilter) {
            const newData = data.filter((d) => {
                return d?.heading
                    ?.toLowerCase()
                    ?.includes(searchFilter.toLowerCase());
            });
            setFilterData(newData);
        } else {
            setFilterData(data);
        }
    }, [searchFilter, data]);

    // filter data according to view
    useEffect(() => {
        if (viewFilter === "all") {
            setFilterData(data);
        }
        console.log({ viewFilter });
    }, [viewFilter, data]);

    // slicing data according to paginate
    useEffect(() => {
        if (filterData.length) {
            setTotalItem(filterData.length);
            const startIndex = (currentPage - 1) * perPageItem;
            const endIndex = currentPage * perPageItem;
            setSlicedData(filterData.slice(startIndex, endIndex));
        } else {
            setTotalItem((prev) => prev);
            setSlicedData([]);
        }
    }, [currentPage, perPageItem, filterData]);

    // on filter function
    const onFilter = ({ search, date, view }) => {
        if (JSON.stringify(date) !== JSON.stringify(dateFilter)) {
            setDateFilter({ ...date });
        }
        setSearchFilter(search);
        setViewFilter(view);
    };

    return (
        <div>
            <FilterBar onFilter={onFilter} change={true} />
            {(isLoading || isFetching) &&
                _.fill(Array(perPageItem), "*").map((v, i) => (
                    <RequiredActionCard_Loader key={i} />
                ))}
            {!isLoading &&
                !isFetching &&
                slicedData.map((data, i) => {
                    return (
                        <RequiredActionsCard
                            key={i}
                            data={data}
                            status={"expire"}
                        />
                    );
                })}
        </div>
    );
};

export default ExpireRequiredAction;